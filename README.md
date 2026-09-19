# FinTutor – AI-Powered Tutoring Platform

FinTutor is a Laravel-based AI-powered tutoring web application designed to streamline session planning, live note-taking, and post-session insights for tutors, while providing students with a clean, focused dashboard to track their learning progress, homework, and session summaries.

---

## Live Demo

- **URL:** https://fintutor.freedev.app/

### Demo Credentials

| Role        | Username                     | Password |
| :---------- | :--------------------------- | :------- |
| **Tutor**   | tutor@fintutor.freedev.app   | password |
| **Student** | student@fintutor.freedev.app | password |

---

## Features

### Tutor Portal

- **Student Profile Management:** Create, view, and manage assigned student profiles.
- **Smart Scheduling:** Schedule sessions with built-in server-side validation to prevent overlapping tutor appointments.
- **AI Pre-Session Planning:** Automatically generate structured lesson plans (objectives, timed outlines, and practice questions) using Gemini AI before a session starts.
- **Live Interactive Sessions:**
  - Launch live tutoring sessions (`in_progress` state).
  - Real-time live note autosaving with JavaScript debouncing to minimize unnecessary HTTP requests.
- **AI Post-Session Debrief:** Automatically analyze live notes to extract key session summaries, tailored homework assignments, and focus areas for the next lesson.
- **Progress Analytics:** Generate comprehensive student progress summaries derived from historical session debriefs.

### Student Portal

- **Simplified Dashboard:** Clean view of upcoming, live, and completed tutoring sessions.
- **Structured Learning Resources:** Access AI-generated session plans, lesson objectives, summaries, and assigned homework.
- **Profile Management:** Update personal details and manage portal access credentials securely.
- **Role-Based Content Isolation:** Live tutor notes and sensitive administrative controls remain strictly hidden from student access.

---

## Session Lifecycle

Sessions follow a strictly server-enforced workflow state machine:

```
[ Scheduled ] ──> [ In Progress ] ──> [ Completed ] ──> [ AI Reviewed ]
```

1. **Scheduled:** The session is booked. Tutors can pre-generate AI lesson plans.
2. **In Progress:** Started by the tutor. Live notes are taken with debounced autosaving.
3. **Completed:** Session ended by tutor. Normal note editing is locked.
4. **AI Reviewed:** Gemini AI analyzes notes to generate summary, homework, and progress updates.

---

## AI Integration & Workflow

FinTutor leverages Gemini AI (`gemini-3.6-flash`) at three critical touchpoints:

1. **Pre-Session Plan Generation:**
   - **Inputs:** Student grade, age, session topic, and additional tutor instructions.
   - **Output:** Structured JSON schema containing lesson objectives, timed topic outlines, and practice questions.
2. **Post-Session Debrief:**
   - **Inputs:** Tutor's live notes from the completed session.
   - **Output:** Summarized core concepts covered, custom homework assignments, and targeted focus areas.
3. **Student Progress Summary:**
   - **Inputs:** Historical aggregate of past session debriefs.
   - **Output:** Holistic progress tracking, highlighting mastered concepts, recent improvements, and identified learning gaps.

---

## Tech Stack

- **Backend Framework:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL
- **Frontend Template Engine:** Blade, Bootstrap 5
- **Interactivity:** Vanilla JavaScript (AJAX, Debounced Fetch API)
- **AI Integration:** Google Gemini AI API (`gemini-3.6-flash`) via dedicated Laravel Service Provider

---

## Database Schema & Architecture

The core relational data model consists of three primary entities:

- **`users`**: Handles authentication, user credentials, and role differentiation (`tutor` vs. `student`).
- **`students`**: Extends `users` with specific student metadata (grade, age, learning profiles).
- **`tutor_sessions`**: Main operational table linking tutors and students via foreign key constraints (`tutor_id`, `student_id`). Stores lifecycle status, raw live notes, structured AI plans, debriefs, and scheduling metadata.

---

## Security & Access Control

- **Role-Based Middleware:** Strict separation between tutor and student routes.
- **Ownership Validation:** Server-side verification ensures users only view or modify sessions they own or participate in.
- **State Lock Rules:** Completed/AI-Reviewed sessions are locked against unauthorized retroactive tampering.
- **Overlapping Prevention:** Server-side scheduling validation prevents tutors from double-booking session slots.

---

## Design Decisions & Limitations

- **Server-Rendered Simplicity:** Built using Laravel Blade and Bootstrap 5 for fast rendering and maintainability without frontend framework bloat.
- **Decoupled AI Layer:** Gemini logic is encapsulated within a dedicated service layer, isolating controller code from external API dependencies.
- **Structured JSON Prompts:** Enforces JSON mode for AI responses to guarantee predictable schema parsing and database storage.
- **Current Limitations:**
  - Pre-session AI plan sub-sections currently save as a monolithic structured block and cannot be individually inline-edited prior to confirmation.
  - Real-time WebSockets/polling for instantaneous student-side session start indicators are not yet implemented.

---
