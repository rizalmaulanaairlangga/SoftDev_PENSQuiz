# Changelog - PENSQuiz

All notable changes to this project will be documented in this file.

## [2026-05-07] - Registration, Dashboard, & Quiz Snapshot System Fixes

### Fixed
- **Registration Flow**: 
    - Added missing `username` field to the registration form to satisfy backend validation.
    - Updated `RegisteredUserController` to support a broader email domain (`@pens.ac.id`) and redirect to the login page with a success message instead of auto-logging in.
- **Quiz Publishing (Form Step 1)**:
    - Fixed a critical data synchronization bug where Major and Course selections were not correctly captured by the Alpine.js validation engine, preventing successful publishing.
    - Standardized dropdown logic to update the parent state directly.
- **Quiz Play (Snapshot System)**:
    - Resolved 500 Server Errors when starting a quiz by implementing the missing Snapshot creation logic.
    - Added a fallback in the attempt start process to generate a snapshot on-the-fly if it's missing, ensuring older quizzes are still playable.
- **Stats Consistency**: Fixed incorrect primary key mapping (`id_major` vs `id`) in the frontend summary view data.

### Added
- **Intelligent Dashboard Empty States**:
    - Introduced actionable call-to-action (CTA) buttons when statistics are zero.
    - Displays "Start Create Quiz" if the user has no quizzes.
    - Displays "Start Play Quiz" if the user has no attempts or recently opened quizzes.
- **Optional Quiz Details**:
    - Folder, Description, and Duration are now officially optional in both frontend and backend.
    - Duration of `0` is now interpreted and displayed as "No Time Limit".

### Technical
- Implemented `createSnapshot()` method in the `MyQuiz` model to capture a point-in-time state of questions and options.
- Refactored `MyQuizController` to trigger snapshot generation upon storage or update of published quizzes.
- Optimized Alpine.js form handler to reduce nested `x-data` scopes, improving state reliability.


## [2026-05-06] - Quiz Functionality & Data Consistency Improvements

### Fixed
- **Option Selection UI**: Fixed a CSS conflict on the play page where selecting an option caused the choice letter (A, B, C...) to disappear due to background color overrides.
- **Checkbox Logic**: Corrected the question type identification in the quiz engine from `multiple_answer` to `checkbox` to match the database, enabling correct multi-select behavior.
- **Seeder Consistency**: 
    - Fixed `OptionSeeder` and `AttemptSeeder` logic which previously failed to distinguish between single and multiple choice questions, leading to incorrect correct-answer counts in seeded data.
    - Standardized types to `multiple_choice` (single answer) and `checkbox` (multiple answers) across all seeders.

### Added
- **Navigation Safety**:
    - Implemented a "Leave Quiz" confirmation modal on the play page to prevent accidental exits.
    - The modal informs users that their progress is saved and the timer will be paused, allowing them to resume later.
    - Integrated `beforeunload` browser event for native navigation protection.
- **Continue Quiz UX**: 
    - Added a confirmation modal when continuing an existing attempt from the quiz detail page.
    - Displays dynamic statistics: the number of remaining unanswered questions and the exact time left.
- **Dynamic Instructions**: The play page now dynamically displays the number of required selections for checkbox questions (e.g., "Select 2 answers").

### Changed
- **Validation Rules**: Updated `MyQuizController` to strictly enforce that `checkbox` questions must have at least 2 correct options, ensuring data integrity for multiple-choice questions.

### Technical
- Refactored `OptionSeeder` to use `shuffle` and `array_slice` for generating a valid subset of correct options for checkbox questions.
- Synchronized frontend and backend question type naming conventions (`multiple_choice` vs `checkbox`).


## [2026-05-04] - Optimization & UI/UX Improvements

### Fixed
- **Question Counts**: Fixed an issue where the number of questions in a quiz was not displayed correctly in the "My Quizzes" and "Folder" views by using `withCount('questions')` in Eloquent queries.
- **SQL Error 500**: Fixed a database error when adding a quiz to a folder caused by an incorrect table name reference in the validation rule.
- **Save as Draft Logic**: 
    - Fixed validation logic to allow saving as draft without a title or major.
    - Added a default title ("Untitled Quiz") for draft quizzes if none is provided.
    - Suppressed native browser "Leave Site?" warnings when the user clicks the "Save as Draft" button.
- **Profile Tab Retention**: Fixed an issue where validation errors during password updates would cause the page to reset to the "Personal Information" tab. It now correctly remains on the "Login Info" tab.

### Added
- **Search Functionality**: Added a search bar to the Folder view, allowing users to filter quizzes by title, tags, major, and course.
- **Unsaved Changes Interceptor**: Implemented a custom Alpine.js-based modal that warns users when they attempt to navigate away from the Quiz Form with unsaved changes.
- **Clean Code Comments**: Added detailed block comments throughout `form.blade.php` and `profile/edit.blade.php` to explain logic for state management, initialization, and UI helpers.

### Changed (UI/UX)
- **Profile Avatar Synchronization**: Updated the profile page avatar to match the dynamic initial-based style used in the top navigation bar.
- **Responsive Quiz Form**: Optimized the quiz form navigation buttons for mobile and tablet views, using a flexible column layout to prevent overlapping.
- **Mobile Profile Navigation**: Transformed the profile sidebar navigation into a custom dropdown menu for mobile and tablet devices, improving space efficiency and usability.
- **Overall Aesthetics**: Refined various UI elements (paddings, border-radius, shadows) across the dashboard, folder, and profile pages to maintain a premium, modern feel.

### Technical
- Refactored `submitQuizForm` to be a method within the Alpine.js component for better state management.
- Integrated `isSubmitting` flag to handle navigation state and prevent duplicate submissions or unwanted warnings.
