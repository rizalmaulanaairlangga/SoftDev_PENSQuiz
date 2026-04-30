<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-4">
        @if($questions->isEmpty())
            <div class="bg-white rounded-[32px] shadow-lg p-12 text-center">
                <p class="text-[20px] font-bold text-gray-400">Tidak ada soal tersedia.</p>
            </div>
        @else
            <div id="quiz-container" class="bg-white rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-12 min-h-[550px] flex flex-col relative overflow-hidden transition-all duration-300">
                
                {{-- Header: Back & Timer --}}
                <div id="header-nav" class="flex items-center justify-between mb-4">
                    <button id="headerBackBtn" onclick="handleHeaderBack()" class="flex items-center gap-2 font-bold text-gray-800 hover:text-black transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                        Back
                    </button>
                    
                    <div id="timerBadge" class="bg-[#eef5fc] text-[#17426a] font-bold px-6 py-2.5 rounded-full flex items-center gap-2 transition-all duration-500 shadow-sm">
                        <span id="timerDisplay">--:--</span>
                        <span id="timerLabel" class="text-[14px] opacity-80">mins left</span>
                    </div>
                </div>

                {{-- VIEW: QUIZ --}}
                <div id="quiz-view" class="flex flex-col flex-grow">
                    <div class="flex-grow">
                        <div class="relative mt-6 mb-10">
                            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-[#518DB7] border-4 border-white text-white px-6 py-2 rounded-full font-bold text-[16px] shadow-lg z-10 whitespace-nowrap leading-none flex items-center justify-center">
                                Question <span id="current-question-num" class="mx-1">1</span> out of {{ count($questions) }}
                            </div>
                            <div class="bg-gradient-to-br from-[#518DB7] via-[#86b5d6] to-[#e0f2fe] rounded-[32px] p-10 md:p-16 min-h-[220px] flex items-center justify-center text-center shadow-xl">
                                <p class="text-white text-[20px] md:text-[24px] font-black leading-relaxed drop-shadow-sm" id="question-content">--</p>
                            </div>
                        </div>
                        <div class="mb-6"><h3 class="text-[20px] font-bold text-gray-900" id="question-instruction">Select one answer</h3></div>
                        <div class="flex flex-col gap-4" id="options-container"></div>
                    </div>
                    <div class="mt-12 flex items-center justify-between">
                        <button id="prevBtn" class="flex items-center gap-2 bg-blue-50 text-[#518DB7] border border-blue-100 font-bold px-8 py-4 rounded-[16px] hover:bg-blue-100 transition disabled:opacity-30 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                            Previous
                        </button>
                        <button id="nextBtn" class="flex items-center gap-2 bg-blue-50 text-[#518DB7] border border-blue-100 font-bold px-10 py-4 rounded-[16px] hover:bg-blue-100 transition">
                            <span id="nextBtnText">Next</span>
                            <svg id="nextBtnIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- VIEW: CHECK ANSWERS --}}
                <div id="check-answers-view" class="hidden flex flex-col flex-grow">
                    <div class="text-center mb-10"><h2 class="text-[32px] font-black text-gray-900 tracking-tight">Check Your Answers</h2></div>
                    <div class="flex flex-wrap justify-center gap-4 mb-12" id="questions-grid"></div>
                    <div class="mt-auto">
                        <div class="space-y-4 mb-10">
                            <h4 class="text-[18px] font-bold text-gray-900">Info</h4>
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#2a6296] via-[#518DB7] to-white border border-blue-200/50 shadow-md"></div>
                                    <span class="font-bold text-gray-700">Answered</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-300 shadow-sm"></div>
                                    <span class="font-bold text-gray-700">Not answered</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button onclick="goToSureSubmitView()" class="flex items-center gap-3 bg-[#124d77] text-white font-bold text-[18px] px-10 py-4 rounded-[20px] hover:bg-[#0e3a5a] transition shadow-2xl shadow-[#124d77]/30 group">
                                Finish <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- VIEW: SURE SUBMIT --}}
                <div id="sure-submit-view" class="hidden flex flex-col flex-grow items-center justify-center text-center">
                    <h2 class="text-[32px] md:text-[40px] font-black text-gray-900 leading-tight">Are you sure you want to submit your answers?</h2>
                    <p id="unanswered-text" class="mt-6 text-[18px] md:text-[20px] font-medium text-gray-500">You still have 0 questions unanswered. <button onclick="goToCheckView()" class="text-blue-600 underline font-bold">Check again</button>.</p>
                    <div class="mt-12 flex justify-end w-full">
                        <button onclick="submitQuizAction()" class="flex items-center gap-3 bg-[#124d77] text-white font-bold text-[18px] px-10 py-4 rounded-[20px] hover:bg-[#0e3a5a] transition shadow-2xl shadow-[#124d77]/30 group">
                            Submit anyway <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- VIEW: RESULT --}}
                <div id="result-view" class="hidden flex flex-col flex-grow items-center justify-center text-center">
                    <h2 class="text-[28px] font-bold text-gray-900">Your Score</h2>
                    <div class="mt-6 text-[100px] md:text-[120px] font-black text-[#f97316] leading-none" id="result-score">0%</div>
                    <div class="mt-12 flex flex-col sm:flex-row gap-4">
                        <button onclick="goToReviewGridView()" class="bg-[#124d77] text-white font-bold text-[16px] px-10 py-4 rounded-full shadow-lg hover:bg-[#0e3a5a] transition">Review Answers</button>
                        <button onclick="toggleRetakeModal(true)" class="bg-[#fdc02a] text-[#17426a] font-bold text-[16px] px-10 py-4 rounded-full shadow-lg hover:bg-[#eab308] transition">Retake Quiz</button>
                    </div>
                </div>

                {{-- VIEW: REVIEW GRID --}}
                <div id="review-grid-view" class="hidden flex flex-col flex-grow">
                    <div class="text-center mb-10"><h2 class="text-[32px] font-black text-gray-900 tracking-tight">Review Your Answers</h2></div>
                    <div class="flex flex-wrap justify-center gap-4 mb-12" id="review-questions-grid"></div>
                    <div class="mt-auto">
                        <div class="space-y-4 mb-10">
                            <h4 class="text-[18px] font-bold text-gray-900">Info</h4>
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-[#22c55e] shadow-md shadow-green-500/20 border border-green-200"></div>
                                    <span class="font-bold text-gray-700">Correct</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-[#ef4444] shadow-md shadow-red-500/20 border border-red-200"></div>
                                    <span class="font-bold text-gray-700">Incorrect</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- VIEW: REVIEW DETAIL --}}
                <div id="review-detail-view" class="hidden flex flex-col flex-grow">
                    <div class="flex-grow">
                        <div class="relative mt-6 mb-10">
                            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-[#124d77] border-4 border-white text-white px-6 py-2 rounded-full font-bold text-[16px] shadow-lg z-10 whitespace-nowrap leading-none flex items-center justify-center">
                                Question <span id="review-current-num" class="mx-1">1</span> out of {{ count($questions) }}
                            </div>
                            <div class="bg-gradient-to-br from-[#124d77] via-[#226391] to-[#6BA9D0] rounded-[32px] p-10 md:p-16 min-h-[220px] flex items-center justify-center text-center shadow-xl">
                                <p class="text-white text-[20px] md:text-[24px] font-black leading-relaxed drop-shadow-sm" id="review-question-content">--</p>
                            </div>
                        </div>
                        <div class="mb-6"><h3 id="review-question-instruction" class="text-[20px] font-bold text-gray-900">Select one answer</h3></div>
                        <div class="flex flex-col gap-4" id="review-options-container"></div>
                    </div>
                    <div class="mt-12 flex items-center justify-between">
                        <button id="reviewPrevBtn" class="flex items-center gap-2 bg-gray-100 text-gray-600 font-bold px-8 py-4 rounded-[16px] hover:bg-gray-200 transition disabled:opacity-30 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg> Previous
                        </button>
                        <button id="reviewNextBtn" class="flex items-center gap-2 bg-gray-100 text-gray-900 font-bold px-10 py-4 rounded-[16px] hover:bg-gray-200 transition">
                            Next <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

            </div>
        @endif
    </div>

    {{-- Retake Modal --}}
    <div id="retakeModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-md transition-all duration-300">
        <div id="retakeContent" class="bg-white w-full max-w-4xl rounded-[40px] p-8 md:p-16 shadow-[0_20px_70px_-10px_rgba(0,0,0,0.1)] relative opacity-0 scale-95 transition-all duration-300 ease-out">
            <button onclick="toggleRetakeModal(false)" class="absolute top-10 left-10 flex items-center gap-2 font-bold text-gray-800 hover:text-black transition group">
                <div class="bg-gray-100 p-2 rounded-full group-hover:bg-gray-200 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg></div> Back
            </button>
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <h2 class="text-[36px] md:text-[48px] font-black text-gray-900 leading-tight tracking-tight">Are you sure you want to retake this quiz?</h2>
                <p class="mt-6 text-[18px] md:text-[22px] font-medium text-gray-500">
                    {{ $attempt->time_limit_minutes ? "A <span class='font-bold text-gray-900'>{$attempt->time_limit_minutes}-minute</span> time count will start if you start now." : "No time limit will be applied to this quiz." }}
                </p>
            </div>
            <div class="flex justify-end mt-4">
                <form method="POST" action="{{ route('quiz.start', $attempt->quiz_id) }}">@csrf
                    <button type="submit" class="bg-[#124d77] text-white font-bold text-[18px] px-10 py-5 rounded-[20px] flex items-center gap-3 hover:bg-[#0e3a5a] transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-[#124d77]/30 group">
                        Start quiz now <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </form>
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
    let currentState = 'quiz'; // quiz, check, sure-submit, result, review-grid, review-detail
    let reviewCurrent = 0;

    const views = {
        quiz: document.getElementById('quiz-view'),
        check: document.getElementById('check-answers-view'),
        'sure-submit': document.getElementById('sure-submit-view'),
        result: document.getElementById('result-view'),
        'review-grid': document.getElementById('review-grid-view'),
        'review-detail': document.getElementById('review-detail-view')
    };

    function switchView(viewName) {
        Object.values(views).forEach(v => v.classList.add('hidden'));
        views[viewName].classList.remove('hidden');
        currentState = viewName;
        
        // Header logic
        const timerBadge = document.getElementById('timerBadge');
        if (['result', 'review-grid', 'review-detail'].includes(viewName)) {
            timerBadge.classList.add('invisible');
        } else {
            timerBadge.classList.remove('invisible');
        }
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function getSavedAnswer(qId) { return answers[qId] ?? answers[String(qId)]; }

    function isQuestionCorrect(q) {
        const userAnswers = getSavedAnswer(q.id_snapshot_question);
        if (!userAnswers || (Array.isArray(userAnswers) && userAnswers.length === 0)) return false;
        
        const correctOptionIds = q.options.filter(o => o.is_correct).map(o => Number(o.id_snapshot_option));
        const userOptionIds = (Array.isArray(userAnswers) ? userAnswers : [userAnswers]).map(Number);
        
        if (correctOptionIds.length !== userOptionIds.length) return false;
        return correctOptionIds.every(id => userOptionIds.includes(id));
    }

    // --- VIEW LOGIC ---

    window.goToQuizView = function(idx = null) {
        if (idx !== null) { current = idx; localStorage.setItem(STORAGE_KEY, current); }
        switchView('quiz');
        renderQuiz();
    };

    window.goToCheckView = function() {
        switchView('check');
        renderCheckGrid();
    };

    window.goToSureSubmitView = function() {
        const unansweredCount = questions.filter(q => {
            const a = getSavedAnswer(q.id_snapshot_question);
            return !a || (Array.isArray(a) && a.length === 0);
        }).length;
        document.getElementById('unanswered-text').innerHTML = `You still have <span class="font-bold text-gray-900">${unansweredCount}</span> questions unanswered. <button onclick="goToCheckView()" class="text-blue-600 underline font-bold">Check again</button>.`;
        switchView('sure-submit');
    };

    window.goToResultView = function() {
        const correctCount = questions.filter(isQuestionCorrect).length;
        const score = Math.round((correctCount / questions.length) * 100);
        document.getElementById('result-score').innerText = `${score}%`;
        switchView('result');
    };

    window.goToReviewGridView = function() {
        switchView('review-grid');
        renderReviewGrid();
    };

    window.goToReviewDetailView = function(idx) {
        reviewCurrent = idx;
        switchView('review-detail');
        renderReviewDetail();
    };

    window.handleHeaderBack = function() {
        if (currentState === 'quiz') window.location.href = '{{ route('quizzes.index') }}';
        else if (currentState === 'check') goToQuizView();
        else if (currentState === 'sure-submit') goToCheckView();
        else if (currentState === 'result') window.location.href = '{{ route('quizzes.index') }}';
        else if (currentState === 'review-grid') goToResultView();
        else if (currentState === 'review-detail') goToReviewGridView();
    };

    // --- RENDERERS ---

    function renderQuiz() {
        const q = questions[current];
        document.getElementById('current-question-num').innerText = q.number;
        document.getElementById('question-content').innerText = q.content;
        
        const container = document.getElementById('options-container');
        container.innerHTML = '';
        const multiple = q.question_type === 'multiple_answer';
        document.getElementById('question-instruction').innerText = multiple ? `Select ${q.correct_count || 'multiple'} answers` : 'Select one answer';

        q.options.forEach((opt, i) => {
            const selected = (Array.isArray(getSavedAnswer(q.id_snapshot_question)) ? getSavedAnswer(q.id_snapshot_question) : [getSavedAnswer(q.id_snapshot_question)]).map(Number).includes(Number(opt.id_snapshot_option));
            const btn = document.createElement('button');
            btn.className = `flex items-center gap-6 bg-[#f5f5f5] rounded-[24px] p-5 text-left transition-all hover:bg-gray-200 group ring-2 ${selected ? 'ring-[#518DB7] bg-blue-50/50' : 'ring-transparent'}`;
            btn.innerHTML = `<div class="bg-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-[18px] text-gray-700 shrink-0 shadow-sm ${selected ? 'bg-[#518DB7] !text-white' : ''}">${String.fromCharCode(65 + i)}</div><div class="text-[17px] md:text-[19px] font-medium text-gray-800">${opt.content}</div>`;
            btn.onclick = () => {
                let currentAnswers = Array.isArray(getSavedAnswer(q.id_snapshot_question)) ? [...getSavedAnswer(q.id_snapshot_question)].map(Number) : (getSavedAnswer(q.id_snapshot_question) ? [Number(getSavedAnswer(q.id_snapshot_question))] : []);
                if (multiple) {
                    const idx = currentAnswers.indexOf(Number(opt.id_snapshot_option));
                    if (idx >= 0) {
                        currentAnswers.splice(idx, 1);
                    } else {
                        if (currentAnswers.length >= q.correct_count) {
                            currentAnswers.shift(); // Remove the first one selected
                        }
                        currentAnswers.push(Number(opt.id_snapshot_option));
                    }
                } else {
                    currentAnswers = [Number(opt.id_snapshot_option)];
                }
                answers[q.id_snapshot_question] = multiple ? currentAnswers : currentAnswers[0];
                fetch('{{ route('attempt.saveAnswer') }}', { method: 'POST', headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: JSON.stringify({ attempt_id: attemptId, question_id: q.id_snapshot_question, option_ids: currentAnswers, duration_seconds: durationSeconds }) });
                renderQuiz();
            };
            container.appendChild(btn);
        });
        document.getElementById('prevBtn').disabled = current === 0;
        document.getElementById('nextBtnText').innerText = current === questions.length - 1 ? 'Submit' : 'Next';
    }

    function renderCheckGrid() {
        const grid = document.getElementById('questions-grid');
        grid.innerHTML = '';
        questions.forEach((q, i) => {
            const ans = getSavedAnswer(q.id_snapshot_question);
            const answered = ans && (!Array.isArray(ans) || ans.length > 0);
            const btn = document.createElement('button');
            btn.className = `w-12 h-12 rounded-2xl font-black text-[18px] transition-all hover:scale-110 active:scale-95 border ${answered ? 'bg-gradient-to-br from-[#2a6296] via-[#518DB7] to-white text-[#17426a] border-blue-300/30' : 'bg-gray-300 text-gray-500 border-transparent'}`;
            btn.innerText = i + 1;
            btn.onclick = () => goToQuizView(i);
            grid.appendChild(btn);
        });
    }

    function renderReviewGrid() {
        const grid = document.getElementById('review-questions-grid');
        grid.innerHTML = '';
        questions.forEach((q, i) => {
            const correct = isQuestionCorrect(q);
            const btn = document.createElement('button');
            btn.className = `w-12 h-12 rounded-2xl font-black text-[18px] text-white transition-all hover:scale-110 active:scale-95 shadow-lg border-2 ${correct ? 'bg-[#22c55e] border-green-200' : 'bg-[#ef4444] border-red-200'}`;
            btn.innerText = i + 1;
            btn.onclick = () => goToReviewDetailView(i);
            grid.appendChild(btn);
        });
    }

    function renderReviewDetail() {
        const q = questions[reviewCurrent];
        document.getElementById('review-current-num').innerText = q.number;
        document.getElementById('review-question-content').innerText = q.content;
        const container = document.getElementById('review-options-container');
        container.innerHTML = '';
        const multiple = q.question_type === 'multiple_answer';
        document.getElementById('review-question-instruction').innerText = multiple ? `Select ${q.correct_count || 'multiple'} answers` : 'Select one answer';
        const userAnswers = (Array.isArray(getSavedAnswer(q.id_snapshot_question)) ? getSavedAnswer(q.id_snapshot_question) : [getSavedAnswer(q.id_snapshot_question)]).map(Number);

        q.options.forEach((opt, i) => {
            const isCorrect = opt.is_correct;
            const isSelected = userAnswers.includes(Number(opt.id_snapshot_option));
            const colorClass = isCorrect ? 'bg-[#22c55e] text-white border-green-300' : (isSelected ? 'bg-[#ef4444] text-white border-red-300' : 'bg-gray-100 text-gray-800 border-transparent');
            const div = document.createElement('div');
            div.className = `flex items-center gap-6 rounded-[24px] p-5 text-left border-2 ${colorClass}`;
            div.innerHTML = `<div class="bg-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-[18px] text-gray-700 shrink-0 shadow-sm">${String.fromCharCode(65 + i)}</div><div class="text-[17px] md:text-[19px] font-medium leading-snug">${opt.content}</div>`;
            container.appendChild(div);
        });
        document.getElementById('reviewPrevBtn').disabled = reviewCurrent === 0;
        document.getElementById('reviewPrevBtn').onclick = () => { if (reviewCurrent > 0) goToReviewDetailView(reviewCurrent - 1); };
        document.getElementById('reviewNextBtn').onclick = () => { if (reviewCurrent < questions.length - 1) goToReviewDetailView(reviewCurrent + 1); else goToReviewGridView(); };
        document.getElementById('reviewNextBtn').innerText = reviewCurrent === questions.length - 1 ? 'Back to Grid' : 'Next';
    }

    // --- ACTIONS ---

    document.getElementById('prevBtn').onclick = () => { if (current > 0) { current--; localStorage.setItem(STORAGE_KEY, current); renderQuiz(); window.scrollTo({ top: 0, behavior: 'smooth' }); } };
    document.getElementById('nextBtn').onclick = () => { if (current < questions.length - 1) { current++; localStorage.setItem(STORAGE_KEY, current); renderQuiz(); window.scrollTo({ top: 0, behavior: 'smooth' }); } else { goToCheckView(); } };

    window.submitQuizAction = async function() {
        pauseTimer();
        await fetch('{{ route('attempt.submit') }}', { method: 'POST', headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: JSON.stringify({ attempt_id: attemptId, duration_seconds: durationSeconds }) });
        localStorage.removeItem(STORAGE_KEY);
        goToResultView();
    };

    window.toggleRetakeModal = function(show) {
        const m = document.getElementById('retakeModal');
        const c = document.getElementById('retakeContent');
        if (show) { m.classList.replace('hidden', 'flex'); void m.offsetWidth; c.classList.replace('opacity-0', 'opacity-100'); c.classList.replace('scale-95', 'scale-100'); }
        else { c.classList.replace('opacity-100', 'opacity-0'); c.classList.replace('scale-100', 'scale-95'); setTimeout(() => m.classList.replace('flex', 'hidden'), 300); }
    };

    // Timer Logic (simplified for brevity)
    const timeLimitMinutes = {{ $attempt->time_limit_minutes ?? 'null' }};
    let durationSeconds = {{ $attempt->duration_seconds ?? 0 }};
    let timerInterval = setInterval(() => { if (!isPaused) { durationSeconds++; updateTimerUI(); } }, 1000);
    let isPaused = false;
    function pauseTimer() { isPaused = true; }
    function updateTimerUI() {
        if (!timeLimitMinutes) { document.getElementById('timerDisplay').innerText = formatTime(durationSeconds); document.getElementById('timerLabel').innerText = "elapsed"; return; }
        const rem = (timeLimitMinutes * 60) - durationSeconds;
        if (rem <= 0) { document.getElementById('timerDisplay').innerText = "00:00"; submitQuizAction(); clearInterval(timerInterval); return; }
        document.getElementById('timerDisplay').innerText = rem <= 60 ? `${rem} secs` : formatTime(rem);
        document.getElementById('timerLabel').innerText = rem <= 60 ? "left" : "mins left";
        document.getElementById('timerBadge').className = rem <= 60 ? "bg-gradient-to-r from-red-400 via-red-600 to-red-400 text-white font-bold px-6 py-2.5 rounded-full flex items-center gap-2 shadow-lg animate-pulse" : (rem <= 180 ? "bg-gradient-to-r from-yellow-300 via-yellow-500 to-yellow-300 text-white font-bold px-6 py-2.5 rounded-full flex items-center gap-2 shadow-md" : "bg-[#eef5fc] text-[#17426a] font-bold px-6 py-2.5 rounded-full flex items-center gap-2 shadow-sm");
    }
    function formatTime(s) { return `${Math.floor(s/60).toString().padStart(2,'0')}:${(s%60).toString().padStart(2,'0')}`; }

    renderQuiz();
    updateTimerUI();
});
</script>
</x-app-layout>