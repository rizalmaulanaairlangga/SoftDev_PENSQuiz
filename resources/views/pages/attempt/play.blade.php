<x-app-layout>
    <div class=" min-w-full py-1">
        @if($questions->isEmpty())
            <div class="bg-white rounded-[20px] shadow p-6">
                <p class="text-black/70">Tidak ada soal tersedia.</p>
            </div>
        @else
            <div class="w-full">

                {{-- Question Card --}}
                <div class="bg-white rounded-[24px] p-6 md:p-8 w-full shadow-[0_8px_16px_-4px_rgba(0,0,0,0.15)]">

                    <h2 class="text-[24px] md:text-[28px] font-bold mb-3" id="question-title">
                        Question {{ $questions[0]['number'] ?? 1 }}
                    </h2>

                    <p class="text-black/80 mb-5 text-[16px] md:text-[18px]" id="question-content">
                        {{ $questions[0]['content'] ?? '-' }}
                    </p>

                    <p class="font-semibold mb-4" id="question-instruction">
                        {{ (($questions[0]['answer_count'] ?? 1) > 1) ? 'Select two answers' : 'Pick one answer' }}
                    </p>

                    {{-- Options --}}
                    <div class="flex flex-col gap-3" id="options-container">
                        @foreach($questions[0]['options'] ?? [] as $i => $opt)
                            <div
                                class="option flex items-start gap-4 bg-[#f5f5f5] rounded-[20px] p-4 cursor-pointer hover:bg-gray-200 transition"
                                data-option-id="{{ $opt['id_snapshot_option'] }}"
                            >
                                <div class="bg-white rounded-full w-10 h-10 flex items-center justify-center font-semibold shrink-0">
                                    {{ chr(65 + $i) }}
                                </div>

                                <div class="pt-1 text-[15px] leading-6">
                                    {{ $opt['content'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Bottom Navigation --}}
                    <div class="flex items-center justify-between gap-3 mt-8">
                        <button id="prevBtn"
                            class="bg-[#f5f5f5] px-5 py-3 rounded-xl font-medium hover:bg-gray-200 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            ← Previous
                        </button>

                        <div class="flex items-center justify-center gap-2 flex-wrap" id="question-pagination">
                            {{-- rendered by JS --}}
                        </div>

                        <button id="nextBtn"
                            class="bg-[#f5f5f5] px-5 py-3 rounded-xl font-medium hover:bg-gray-200 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Next →
                        </button>
                    </div>
                    

                </div>
            </div>
        @endif
    </div>


    {{-- Modal exit quiz --}}
    <div id="exitModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/40">
        <div class="w-[min(92vw,520px)] rounded-[24px] bg-white p-6 text-center shadow-2xl">
            <h2 class="text-[24px] font-bold mb-2">Leave Quiz?</h2>
            <p class="text-black/70 mb-6">You still have an ongoing attempt.</p>

            <div class="flex items-center justify-center gap-3">
                <button type="button" id="saveExitBtn"
                    class="rounded-xl bg-[#104876] px-5 py-3 text-white font-semibold">
                    Save & Exit
                </button>

                <button type="button" id="discardExitBtn"
                    class="rounded-xl bg-red-500 px-5 py-3 text-white font-semibold">
                    Discard
                </button>

                <button type="button" id="cancelExitBtn"
                    class="rounded-xl bg-gray-200 px-5 py-3 text-black font-semibold">
                    Cancel
                </button>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const questions = @json($questions);
    const attemptId = {{ $attempt->id_attempt }};
    const STORAGE_KEY = `quiz_current_${attemptId}`;

    let current = Number(localStorage.getItem(STORAGE_KEY)) || 0;
    if (current < 0 || current >= questions.length) current = 0;

    let answers = @json($savedAnswers ?? []);

    const titleEl = document.getElementById('question-title');
    const contentEl = document.getElementById('question-content');
    const instructionEl = document.getElementById('question-instruction');
    const optionsContainer = document.getElementById('options-container');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const paginationEl = document.getElementById('question-pagination');

    const exitModal = document.getElementById('exitModal');
    const saveExitBtn = document.getElementById('saveExitBtn');
    const discardExitBtn = document.getElementById('discardExitBtn');
    const cancelExitBtn = document.getElementById('cancelExitBtn');

    let pendingNavigation = null;
    let leaveConfirmed = false;

    function getSavedAnswer(qId) {
        return answers[qId] ?? answers[String(qId)];
    }

    function isMultipleQuestion(q) {
        return q.question_type === 'multi';
    }

    function isOptionSelected(qId, optId, multiple) {
        const saved = getSavedAnswer(qId);
        const normalizedOptId = Number(optId);

        if (multiple) {
            return Array.isArray(saved) && saved.map(Number).includes(normalizedOptId);
        }

        return Number(saved) === normalizedOptId;
    }

    function saveToServer(q, optionIds) {
        fetch('{{ route('attempt.saveAnswer') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                attempt_id: attemptId,
                question_id: q.id_snapshot_question,
                option_ids: optionIds.map(Number)
            })
        });
    }

    function toggleAnswer(q, optId) {
        const qId = q.id_snapshot_question;
        const multiple = isMultipleQuestion(q);
        optId = Number(optId);

        let result;

        if (multiple) {
            const currentValue = Array.isArray(getSavedAnswer(qId))
                ? [...getSavedAnswer(qId)].map(Number)
                : [];

            const index = currentValue.indexOf(optId);

            if (index >= 0) {
                currentValue.splice(index, 1);
            } else {
                currentValue.push(optId);
            }

            answers[qId] = currentValue;
            result = currentValue;
        } else {
            answers[qId] = optId;
            result = [optId];
        }

        saveToServer(q, result);
        render();
    }

    function renderPagination() {
        paginationEl.innerHTML = '';

        questions.forEach((q, index) => {
            const saved = getSavedAnswer(q.id_snapshot_question);
            const answered = Array.isArray(saved)
                ? saved.length > 0
                : saved !== undefined && saved !== null;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerText = q.number ?? (index + 1);

            btn.className = `
                w-12 h-12 rounded-lg font-semibold transition
                ${index === current ? 'bg-[#104876] text-white' : ''}
                ${index !== current && answered ? 'bg-green-500 text-white' : ''}
                ${index !== current && !answered ? 'bg-gray-200 text-black' : ''}
                hover:opacity-90
            `;

            btn.onclick = () => {
                current = index;
                localStorage.setItem(STORAGE_KEY, current);
                render();
            };

            paginationEl.appendChild(btn);
        });
    }

    function renderOptions(q) {
        const multiple = isMultipleQuestion(q);

        instructionEl.innerText = multiple
            ? 'Select all correct answers'
            : 'Pick one answer';

        optionsContainer.innerHTML = '';

        q.options.forEach((opt, i) => {
            const selected = isOptionSelected(
                q.id_snapshot_question,
                opt.id_snapshot_option,
                multiple
            );

            const div = document.createElement('button');
            div.type = 'button';

            div.className = `
                option flex items-center gap-4 bg-[#f5f5f5] rounded-[20px] p-4 text-left transition duration-200
                hover:bg-gray-200
                ${selected ? 'bg-blue-100 border-2 border-blue-500 scale-[1.01]' : 'border-2 border-transparent'}
            `;

            div.innerHTML = `
                <div class="bg-white rounded-full w-10 h-10 flex items-center justify-center font-semibold shrink-0">
                    ${String.fromCharCode(65 + i)}
                </div>
                <div class="pt-1 text-[15px] leading-6">${opt.content}</div>
            `;

            div.onclick = () => toggleAnswer(q, opt.id_snapshot_option);
            optionsContainer.appendChild(div);
        });
    }

    function render() {
        if (!questions.length) return;

        const q = questions[current];
        titleEl.innerText = `Question ${q.number ?? (current + 1)}`;
        contentEl.innerText = q.content ?? '-';

        renderOptions(q);
        renderPagination();

        prevBtn.disabled = current === 0;
        nextBtn.disabled = current === questions.length - 1;
    }

    prevBtn.onclick = () => {
        if (current > 0) {
            current--;
            localStorage.setItem(STORAGE_KEY, current);
            render();
        }
    };

    nextBtn.onclick = () => {
        if (current < questions.length - 1) {
            current++;
            localStorage.setItem(STORAGE_KEY, current);
            render();
        }
    };

    function openExitModal(url) {
        pendingNavigation = url || null;
        exitModal.classList.remove('hidden');
        exitModal.classList.add('flex');
    }

    function closeExitModal() {
        pendingNavigation = null;
        exitModal.classList.add('hidden');
        exitModal.classList.remove('flex');
    }

    // Intercept navigasi internal saja
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;
        if (link.target === '_blank') return;
        if (link.closest('#exitModal')) return;
        if (link.hasAttribute('data-no-quiz-guard')) return;

        // jangan ganggu anchor internal kecil
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#')) return;

        e.preventDefault();
        openExitModal(link.href);
    });

    saveExitBtn.addEventListener('click', async () => {
        leaveConfirmed = true;

        await fetch('{{ route('attempt.exit') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                attempt_id: attemptId,
                action: 'save'
            })
        });

        if (pendingNavigation) {
            window.location.href = pendingNavigation;
        } else {
            closeExitModal();
        }
    });

    discardExitBtn.addEventListener('click', async () => {
        leaveConfirmed = true;

        await fetch('{{ route('attempt.exit') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                attempt_id: attemptId,
                action: 'discard'
            })
        });

        localStorage.removeItem(STORAGE_KEY);
        window.location.href = '{{ route('quizzes.index') }}';
    });

    cancelExitBtn.addEventListener('click', () => {
        closeExitModal();
    });

    // Penting: tidak pakai beforeunload supaya tidak muncul prompt bawaan browser
    window.addEventListener('pageshow', () => {
        render();
    });

    render();
});
</script>
</x-app-layout>