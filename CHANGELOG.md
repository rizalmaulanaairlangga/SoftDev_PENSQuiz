# Changelog

All notable changes to the PENSQuiz project will be documented in this file.

## [Unreleased] - 2026-05-02

### Added
- **Quiz Creation Wizard**:
  - Implemented a new 3-stage workflow (Detail -> Questions -> Summary) for both creating and editing quizzes.
  - Added a "Summary" stage for final review before publishing or saving as draft.
  - Integrated a custom validation modal for the "Publish" action to ensure data integrity.
  - Added smooth scroll-to-top transition between wizard stages.
  - Implemented dynamic "Infinity" icon for quizzes without a time limit in the summary view.
- **Profile Management**:
  - Redesigned the Profile page with a modern dual-tab sidebar layout (Personal Information & Login Information).
  - Added a live academic semester calculator using Alpine.js that updates instantly based on the "Year of Entry".
  - Improved form validation visibility with a global error alert system.
  - Added missing `username` field to the personal info form to ensure full database synchronization.
- **Assets & Icons**:
  - Integrated custom images for header dropdown menus (`img_myquizzes.png`, `img_create_quiz.png`, `img_profile.png`, etc.).
  - Added a standard infinity SVG for the quiz duration preview.

### Changed
- **UI/UX Refinement**:
  - Updated primary action buttons to use the brand blue color `#528FB9`.
  - Standardized navigation button sizes across the quiz wizard for better consistency.
  - Removed redundant icons from the duration input to prioritize native browser number controls.
  - Redesigned the header profile dropdown with a modern card style and icon-left composition.
  - Styled the sidebar navigation with soft blue hover effects and clear active/inactive states.
- **Backend**:
  - Updated `ProfileUpdateRequest` to validate `first_name`, `last_name`, `username`, `major_id`, and `year_of_entry`.
  - Enhanced `MyQuizController` and `ProfileController` to handle the new 3-stage wizard and extended profile fields.

### Fixed
- Fixed a critical syntax error in the Alpine.js `quizFormHandler` that caused the create quiz page to appear blank.
- Resolved an issue where "Year of Entry" changes were not being persisted to the database due to missing validation fields.
- Fixed Z-index conflicts in question cards that caused "Correct Answer" dropdowns to be clipped.
- Corrected the academic semester formula to properly account for odd/even semester cycles.
