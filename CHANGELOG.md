# Changelog - PENSQuiz

All notable changes to this project will be documented in this file.

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
