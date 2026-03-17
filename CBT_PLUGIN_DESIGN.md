# CBT (Computer-Based Training) Plugin Design Document

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Database Schema Design](#database-schema-design)
3. [Main Features & Functionality](#main-features--functionality)
4. [Technical Requirements](#technical-requirements)
5. [API Endpoints](#api-endpoints)
6. [Security Measures](#security-measures)
7. [User Interface Wireframes](#user-interface-wireframes)
8. [User Workflow](#user-workflow)
9. [Technology Stack](#technology-stack)
10. [Plugin Structure](#plugin-structure)

---

## 1. Executive Summary

CBT Plugin adalah modul tambahan untuk YayasanEdu-App yang memungkinkan platform pembelajaran online untuk melakukan:

- **Course Management**: Pembuatan dan pengelolaan kursus/modul pembelajaran
- **Quiz System**: Sistem ujian online dengan berbagai jenis soal
- **Progress Tracking**: Pelacakan kemajuan belajar pengguna
- **Analytics & Reporting**: Laporan statistik dan analytics
- **Certificate System**: Sistem sertifikasi penyelesaian kursus

Plugin ini dirancang untuk kompatibilitas dengan platform Laravel-based multi-tenant yang sudah ada, dengan kemampuan integrasi ke WordPress/LMS lainnya melalui API.

---

## 2. Database Schema Design

### 2.1 Overview Database

Database CBT menggunakan pendekatan module-specific tables yang terintegrasi dengan database utama platform.

### 2.2 Tabel Utama

#### 2.2.1 Tabel: `cbt_courses`

Tabel utama untuk menyimpan informasi kursus.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| tenant_id | BIGINT UNSIGNED | Foreign Key ke tenants |
| title | VARCHAR(255) | Judul kursus |
| slug | VARCHAR(255) | URL slug unik |
| description | TEXT | Deskripsi kursus |
| thumbnail | VARCHAR(500) | Path gambar thumbnail |
| category_id | BIGINT UNSIGNED | Foreign Key ke course_categories |
| difficulty_level | ENUM | beginner, intermediate, advanced |
| duration_hours | INT | Estimasi jam penyelesaian |
| is_published | BOOLEAN | Status publikasi |
| is_free | BOOLEAN | Kursus gratis atau berbayar |
| price | DECIMAL(10,2) | Harga kursus (jika berbayar) |
| certificate_id | BIGINT UNSIGNED | Foreign Key ke certificates |
| passing_score | INT | Nilai minimal lulus (default 70) |
| created_by | BIGINT UNSIGNED | Foreign Key ke users |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_courses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    thumbnail VARCHAR(500),
    category_id BIGINT UNSIGNED,
    difficulty_level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    duration_hours INT DEFAULT 0,
    is_published BOOLEAN DEFAULT FALSE,
    is_free BOOLEAN DEFAULT TRUE,
    price DECIMAL(10,2) DEFAULT 0.00,
    certificate_id BIGINT UNSIGNED,
    passing_score INT DEFAULT 70,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES cbt_course_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (certificate_id) REFERENCES cbt_certificates(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

#### 2.2.2 Tabel: `cbt_modules` (Modul/Bab dalam Kursus)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| course_id | BIGINT UNSIGNED | Foreign Key ke cbt_courses |
| title | VARCHAR(255) | Judul modul |
| description | TEXT | Deskripsi modul |
| order | INT | Urutan modul dalam kursus |
| is_published | BOOLEAN | Status publikasi |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_modules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    order_index INT DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES cbt_courses(id) ON DELETE CASCADE
);
```

#### 2.2.3 Tabel: `cbt_lessons` (Materi Pelajaran)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| module_id | BIGINT UNSIGNED | Foreign Key ke cbt_modules |
| title | VARCHAR(255) | Judul lesson |
| content_type | ENUM | video, text, document, quiz |
| content | LONGTEXT | Konten (HTML/URL video/document) |
| video_url | VARCHAR(500) | URL video (YouTube/Vimeo/local) |
| duration_minutes | INT | Durasi dalam menit |
| order | INT | Urutan lesson dalam modul |
| is_published | BOOLEAN | Status publikasi |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content_type ENUM('video', 'text', 'document', 'quiz', 'assignment') DEFAULT 'text',
    content LONGTEXT,
    video_url VARCHAR(500),
    attachment_url VARCHAR(500),
    duration_minutes INT DEFAULT 0,
    order_index INT DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES cbt_modules(id) ON DELETE CASCADE
);
```

#### 2.2.4 Tabel: `cbt_quizzes` (Kuis/Ujian)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| lesson_id | BIGINT UNSIGNED | Foreign Key ke cbt_lessons (nullable untuk quiz standalone) |
| course_id | BIGINT UNSIGNED | Foreign Key ke cbt_courses |
| title | VARCHAR(255) | Judul kuis |
| description | TEXT | Deskripsi kuis |
| quiz_type | ENUM | assignment, exam, practice |
| time_limit_minutes | INT | Batas waktu dalam menit (0 = tanpa batas) |
| attempt_limit | INT | Batas jumlah percobaan (0 = unlimited) |
| shuffle_questions | BOOLEAN | Acak urutan pertanyaan |
| shuffle_answers | BOOLEAN | Acak urutan jawaban |
| show_correct_answers | BOOLEAN | Tampilkan jawaban benar setelah submit |
| passing_score | INT | Nilai minimal lulus |
| is_published | BOOLEAN | Status publikasi |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_quizzes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id BIGINT UNSIGNED,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    quiz_type ENUM('assignment', 'exam', 'practice') DEFAULT 'exam',
    time_limit_minutes INT DEFAULT 0,
    attempt_limit INT DEFAULT 0,
    shuffle_questions BOOLEAN DEFAULT FALSE,
    shuffle_answers BOOLEAN DEFAULT FALSE,
    show_correct_answers BOOLEAN DEFAULT TRUE,
    passing_score INT DEFAULT 70,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES cbt_lessons(id) ON DELETE SET NULL,
    FOREIGN KEY (course_id) REFERENCES cbt_courses(id) ON DELETE CASCADE
);
```

#### 2.2.5 Tabel: `cbt_questions` (Soal)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| quiz_id | BIGINT UNSIGNED | Foreign Key ke cbt_quizzes |
| question_type | ENUM | multiple_choice, true_false, essay, drag_drop, matching |
| question_text | LONGTEXT | Isi pertanyaan |
| media_url | VARCHAR(500) | URL gambar/audio/video soal |
| media_type | ENUM | image, audio, video, none |
| points | INT | Bobot nilai soal |
| explanation | TEXT | Penjelasan jawaban (untuk review) |
| order | INT | Urutan soal dalam kuis |
| is_active | BOOLEAN | Status aktif |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id BIGINT UNSIGNED NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'essay', 'drag_drop', 'matching') NOT NULL,
    question_text LONGTEXT NOT NULL,
    media_url VARCHAR(500),
    media_type ENUM('image', 'audio', 'video', 'none') DEFAULT 'none',
    points INT DEFAULT 1,
    explanation TEXT,
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES cbt_quizzes(id) ON DELETE CASCADE
);
```

#### 2.2.6 Tabel: `cbt_answers` (Jawaban Opsi)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| question_id | BIGINT UNSIGNED | Foreign Key ke cbt_questions |
| answer_text | LONGTEXT | Isi jawaban |
| is_correct | BOOLEAN | Jawaban benar atau salah |
| order | INT | Urutan opsi jawaban |
| match_item_id | BIGINT UNSIGNED | Untuk question type matching |

```sql
CREATE TABLE cbt_answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id BIGINT UNSIGNED NOT NULL,
    answer_text LONGTEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    order_index INT DEFAULT 0,
    match_item_id BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES cbt_questions(id) ON DELETE CASCADE,
    FOREIGN KEY (match_item_id) REFERENCES cbt_answers(id) ON DELETE SET NULL
);
```

#### 2.2.7 Tabel: `cbt_enrollments` (Pendaftaran Kursus)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| user_id | BIGINT UNSIGNED | Foreign Key ke users |
| course_id | BIGINT UNSIGNED | Foreign Key ke cbt_courses |
| enrolled_at | TIMESTAMP | Tanggal pendaftaran |
| completed_at | TIMESTAMP | Tanggal penyelesaian (NULL jika belum) |
| status | ENUM | enrolled, in_progress, completed, dropped |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_enrollments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    status ENUM('enrolled', 'in_progress', 'completed', 'dropped') DEFAULT 'enrolled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES cbt_courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id)
);
```

#### 2.2.8 Tabel: `cbt_lesson_progress` (Progress Pembelajaran)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| user_id | BIGINT UNSIGNED | Foreign Key ke users |
| lesson_id | BIGINT UNSIGNED | Foreign Key ke cbt_lessons |
| is_completed | BOOLEAN | Status penyelesaian |
| time_spent_minutes | INT | Waktu yang dihabiskan |
| last_accessed_at | TIMESTAMP | Akses terakhir |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_lesson_progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    lesson_id BIGINT UNSIGNED NOT NULL,
    is_completed BOOLEAN DEFAULT FALSE,
    time_spent_minutes INT DEFAULT 0,
    last_accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES cbt_lessons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_lesson_progress (user_id, lesson_id)
);
```

#### 2.2.9 Tabel: `cbt_quiz_attempts` (Percobaan Kuis)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| user_id | BIGINT UNSIGNED | Foreign Key ke users |
| quiz_id | BIGINT UNSIGNED | Foreign Key ke cbt_quizzes |
| attempt_number | INT | Nomor percobaan |
| started_at | TIMESTAMP | Waktu mulai kuis |
| submitted_at | TIMESTAMP | Waktu submit (NULL jika belum) |
| time_spent_seconds | INT | Waktu pengerjaan dalam detik |
| ip_address | VARCHAR(45) | IP address user |
| user_agent | TEXT | Browser/device info |
| is_completed | BOOLEAN | Status penyelesaian |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_quiz_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    quiz_id BIGINT UNSIGNED NOT NULL,
    attempt_number INT DEFAULT 1,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    submitted_at TIMESTAMP NULL,
    time_spent_seconds INT DEFAULT 0,
    ip_address VARCHAR(45),
    user_agent TEXT,
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES cbt_quizzes(id) ON DELETE CASCADE
);
```

#### 2.2.10 Tabel: `cbt_quiz_answers` (Jawaban User)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| attempt_id | BIGINT UNSIGNED | Foreign Key ke cbt_quiz_attempts |
| question_id | BIGINT UNSIGNED | Foreign Key ke cbt_questions |
| answer_id | BIGINT UNSIGNED | Foreign Key ke cbt_answers (untuk objektif) |
| answer_text | LONGTEXT | Jawaban essay |
| is_correct | BOOLEAN | Jawaban benar/salah (untuk objektif) |
| points_earned | DECIMAL(5,2) | Nilai yang diperoleh |
| graded_at | TIMESTAMP | Waktu penilaian (NULL jika belum) |
| graded_by | BIGINT UNSIGNED | ID user yang menilai (untuk essay) |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_quiz_answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    answer_id BIGINT UNSIGNED,
    answer_text LONGTEXT,
    is_correct BOOLEAN NULL,
    points_earned DECIMAL(5,2) DEFAULT 0.00,
    graded_at TIMESTAMP NULL,
    graded_by BIGINT UNSIGNED,
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (attempt_id) REFERENCES cbt_quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES cbt_questions(id) ON DELETE CASCADE,
    FOREIGN KEY (answer_id) REFERENCES cbt_answers(id) ON DELETE SET NULL,
    FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL
);
```

#### 2.2.11 Tabel: `cbt_results` (Hasil Kuis)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| attempt_id | BIGINT UNSIGNED | Foreign Key ke cbt_quiz_attempts |
| total_points | DECIMAL(10,2) | Total nilai maksimal |
| earned_points | DECIMAL(10,2) | Nilai yang diperoleh |
| percentage | DECIMAL(5,2) | Persentase nilai |
| grade | VARCHAR(2) | Grade (A, B, C, D, E) |
| is_passed | BOOLEAN | Status kelulusan |
| certificate_id | BIGINT UNSIGNED | Foreign Key ke certificates (jika lulus) |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id BIGINT UNSIGNED NOT NULL,
    total_points DECIMAL(10,2) DEFAULT 0.00,
    earned_points DECIMAL(10,2) DEFAULT 0.00,
    percentage DECIMAL(5,2) DEFAULT 0.00,
    grade VARCHAR(2),
    is_passed BOOLEAN DEFAULT FALSE,
    certificate_id BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (attempt_id) REFERENCES cbt_quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (certificate_id) REFERENCES cbt_certificates(id) ON DELETE SET NULL
);
```

#### 2.2.12 Tabel: `cbt_certificates` (Sertifikat)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| tenant_id | BIGINT UNSIGNED | Foreign Key ke tenants |
| course_id | BIGINT UNSIGNED | Foreign Key ke cbt_courses |
| template_name | VARCHAR(255) | Nama template |
| template_html | LONGTEXT | Template HTML sertifikat |
| background_image | VARCHAR(500) | Path gambar latar |
| issued_by | VARCHAR(255) | Nama lembaga |
| signature_url | VARCHAR(500) | URL tanda tangan |
| seal_url | VARCHAR(500) | URL cap/stempel |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED,
    course_id BIGINT UNSIGNED NOT NULL,
    template_name VARCHAR(255) NOT NULL,
    template_html LONGTEXT,
    background_image VARCHAR(500),
    issued_by VARCHAR(255),
    signature_url VARCHAR(500),
    seal_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES cbt_courses(id) ON DELETE CASCADE
);
```

#### 2.2.13 Tabel: `cbt_certificate_issued` (Sertifikat Dikeluarkan)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| certificate_id | BIGINT UNSIGNED | Foreign Key ke cbt_certificates |
| user_id | BIGINT UNSIGNED | Foreign Key ke users |
| course_id | BIGINT UNSIGNED | Foreign Key ke cbt_courses |
| certificate_number | VARCHAR(50) | Nomor unik sertifikat |
| issued_at | TIMESTAMP | Tanggal penerbitan |
| expires_at | TIMESTAMP | Tanggal kedaluwarsa (NULL jika permanen) |
| download_url | VARCHAR(500) | URL download PDF |
| verification_code | VARCHAR(64) | Kode verifikasi unik |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_certificate_issued (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    certificate_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    certificate_number VARCHAR(50) NOT NULL UNIQUE,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    download_url VARCHAR(500),
    verification_code VARCHAR(64) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (certificate_id) REFERENCES cbt_certificates(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES cbt_courses(id) ON DELETE CASCADE
);
```

#### 2.2.14 Tabel: `cbt_course_categories` (Kategori Kursus)

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| tenant_id | BIGINT UNSIGNED | Foreign Key ke tenants |
| name | VARCHAR(255) | Nama kategori |
| slug | VARCHAR(255) | URL slug |
| description | TEXT | Deskripsi |
| icon | VARCHAR(100) | Ikon kategori |
| parent_id | BIGINT UNSIGNED | Kategori induk (untuk subkategori) |
| order | INT | Urutan tampil |
| created_at | TIMESTAMP | Timestamp creation |
| updated_at | TIMESTAMP | Timestamp update |

```sql
CREATE TABLE cbt_course_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id BIGINT UNSIGNED,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(100),
    parent_id BIGINT UNSIGNED,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES cbt_course_categories(id) ON DELETE SET NULL
);
```

### 2.3 Diagram Relasi Database

```
cbt_courses
    ├── cbt_modules (one-to-many)
    │   └── cbt_lessons (one-to-many)
    │       └── cbt_quizzes (one-to-many, optional)
    │           └── cbt_questions (one-to-many)
    │               └── cbt_answers (one-to-many)
    ├── cbt_course_categories (many-to-one)
    ├── cbt_enrollments (one-to-many)
    │   └── cbt_lesson_progress (one-to-many)
    ├── cbt_quiz_attempts (one-to-many)
    │   └── cbt_quiz_answers (one-to-many)
    │   └── cbt_results (one-to-one)
    └── cbt_certificates (one-to-one)
        └── cbt_certificate_issued (one-to-many)
```

---

## 3. Main Features & Functionality

### 3.1 Course Management (Admin/Teacher)

#### 3.1.1 Pembuatan Kursus
- **Course Builder**: Interface drag-drop untuk membuat struktur kursus
- **Multi-level Structure**: Kursus → Modul → Lesson
- **Rich Content**: Support video, text, PDF, audio, embed content
- **Course Settings**: Harga, status publikasi, difficulty level
- **Course Catalog**: Tampilan publik untuk student

#### 3.1.2 Modul Management
- Reorder modul dengan drag-drop
- Publikasi per modul
- Deskripsi dan objectives per modul

#### 3.1.3 Lesson/Materi
- **Content Types**:
  - Video (YouTube, Vimeo, upload local)
  - Text/Article (rich text editor)
  - Document (PDF viewer)
  - Quiz (embedded quiz)
  - Assignment (tugas tertulis)
- **Lesson Settings**: Duration, preview, requiring completion

### 3.2 Quiz System

#### 3.2.1 Jenis Quiz/Soal

| Jenis | Deskripsi | Grading |
|-------|-----------|---------|
| **Multiple Choice** | Pilih satu jawaban dari beberapa opsi | Otomatis |
| **True/False** | Pilih benar atau salah | Otomatis |
| **Essay** | Jawaban teks panjang | Manual |
| **Drag & Drop** | Menarik jawaban ke tempat yang benar | Otomatis |
| **Matching** | Mencocokkan item A dengan item B | Otomatis |

#### 3.2.2 Quiz Configuration
- **Timer**: Batas waktu per quiz atau per soal
- **Attempts**: Batas percobaan (unlimited atau terbatas)
- **Shuffle**: Acak pertanyaan dan jawaban
- **Passing Score**: Nilai minimal kelulusan
- **Review**: Tampilkan jawaban benar setelah submit
- **Instant Feedback**: Feedback langsung per soal

#### 3.2.3 Grading System

**Objective Questions (Auto-grading)**:
```php
// Auto-grading logic
function gradeObjectiveQuestion($question, $userAnswer) {
    $correctAnswers = $question->answers()->where('is_correct', true)->get();
    
    switch ($question->question_type) {
        case 'multiple_choice':
        case 'true_false':
            return $userAnswer->answer_id === $correctAnswers->first()->id;
            
        case 'drag_drop':
        case 'matching':
            // Check all correct matches
            $userAnswers = json_decode($userAnswer->answer_text);
            return $this->validateMatches($correctAnswers, $userAnswers);
            
        default:
            return false;
    }
}
```

**Essay Questions (Manual Grading)**:
```php
// Manual grading for essay
function gradeEssayQuestion($attemptId, $questionId, $score, $feedback) {
    $quizAnswer = QuizAnswer::where('attempt_id', $attemptId)
        ->where('question_id', $questionId)
        ->first();
    
    $quizAnswer->update([
        'answer_text' => $quizAnswer->answer_text,
        'points_earned' => $score,
        'feedback' => $feedback,
        'graded_at' => now(),
        'graded_by' => auth()->id()
    ]);
}
```

### 3.3 Progress Tracking

#### 3.3.1 Lesson Progress
- Track video watched percentage
- Mark as complete when lesson finished
- Time spent tracking

#### 3.3.2 Course Progress Calculation
```
Course Progress % = (Completed Lessons / Total Lessons) × 100
```

#### 3.3.3 Student Dashboard
- List enrolled courses
- Progress percentage per course
- Recent activity
- Upcoming deadlines

### 3.4 Analytics & Reporting

#### 3.4.1 Student Analytics
- Courses enrolled and completed
- Quiz scores history
- Time spent learning
- Performance trends

#### 3.4.2 Course Analytics (Admin)
- Enrollment statistics
- Completion rates
- Average quiz scores
- Drop-off points
- Popular lessons

#### 3.4.3 Quiz Analytics
- Question difficulty analysis
- Answer distribution
- Time analysis per question
- Flagged questions for review

### 3.5 Certificate System

#### 3.5.1 Certificate Templates
- Customizable HTML template
- Background image
- Signature and seal
- QR code for verification

#### 3.5.2 Issuance Rules
- Auto-issue on course completion
- Minimum passing score requirement
- Expiration date (optional)

#### 3.5.3 Verification
- Unique verification code
- QR code scanning
- Public verification page

### 3.6 Import/Export Quiz

#### 3.6.1 Import Formats
- CSV
- Excel (.xlsx)
- JSON
- QTI (Question and Test Interoperability)

#### 3.6.2 Export Formats
- CSV
- Excel
- JSON
- PDF (printable quiz)
- QTI

---

## 4. Technical Requirements

### 4.1 Platform Compatibility

| Platform | Status | Notes |
|----------|--------|-------|
| Laravel 10+ | ✓ Primary | Built on existing platform |
| WordPress | ✓ Via API | REST API integration |
| Moodle | ✓ Via API | LTI integration possible |
| Custom LMS | ✓ Via API | RESTful API |

### 4.2 Responsive Design

- **Mobile**: 320px - 767px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px+

**CSS Framework**: Tailwind CSS (already in project)

### 4.3 Performance Requirements

| Metric | Target |
|--------|--------|
| Page Load | < 2 seconds |
| API Response | < 500ms |
| Quiz Timer Sync | Real-time (±1s) |
| Concurrent Users | 1000+ |
| File Upload | Max 100MB |

### 4.4 Browser Support

- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Android)

---

## 5. API Endpoints

### 5.1 Course API

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/cbt/courses | List all courses |
| GET | /api/cbt/courses/{slug} | Get course details |
| POST | /api/cbt/courses | Create course (admin) |
| PUT | /api/cbt/courses/{id} | Update course (admin) |
| DELETE | /api/cbt/courses/{id} | Delete course (admin) |
| POST | /api/cbt/courses/{id}/enroll | Enroll in course |
| GET | /api/cbt/courses/{id}/progress | Get user progress |

### 5.2 Module & Lesson API

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/cbt/courses/{id}/modules | Get course modules |
| POST | /api/cbt/modules | Create module (admin) |
| PUT | /api/cbt/modules/{id} | Update module (admin) |
| GET | /api/cbt/lessons/{id} | Get lesson content |
| POST | /api/cbt/lessons/{id}/complete | Mark lesson complete |
| PUT | /api/cbt/lessons/{id}/progress | Update progress |

### 5.3 Quiz API

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/cbt/quizzes/{id} | Get quiz details |
| GET | /api/cbt/quizzes/{id}/questions | Get quiz questions |
| POST | /api/cbt/quizzes/{id}/start | Start quiz attempt |
| POST | /api/cbt/quizzes/{id}/submit | Submit quiz answers |
| GET | /api/cbt/attempts/{id} | Get attempt details |
| POST | /api/cbt/attempts/{id}/grade | Grade essay (admin) |

### 5.4 Certificate API

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/cbt/certificates | List certificates |
| GET | /api/cbt/certificates/{id} | Get certificate template |
| GET | /api/cbt/certificates/verify/{code} | Verify certificate |
| GET | /api/cbt/my-certificates | Get user certificates |
| GET | /api/cbt/certificates/{id}/download | Download PDF |

### 5.5 Analytics API

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/cbt/analytics/overview | Dashboard overview |
| GET | /api/cbt/analytics/courses | Course analytics |
| GET | /api/cbt/analytics/quizzes | Quiz analytics |
| GET | /api/cbt/analytics/users/{id} | User analytics |

### 5.6 Import/Export API

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/cbt/import/quiz | Import quiz questions |
| GET | /api/cbt/export/quiz/{id} | Export quiz questions |
| POST | /api/cbt/import/course | Import course |
| GET | /api/cbt/export/course/{id} | Export course |

---

## 6. Security Measures

### 6.1 Anti-Cheat System

| Feature | Implementation |
|---------|----------------|
| **Tab Switching Detection** | JavaScript event listener for visibilitychange |
| **IP Monitoring** | Track multiple attempts from different IPs |
| **Time Anomaly** | Flag attempts too fast or too slow |
| **Copy-Paste Prevention** | Disable right-click and copy in quiz mode |
| **Proctoring Integration** | Support for third-party proctoring APIs |
| **Random Question Order** | Shuffle questions per attempt |

### 6.2 Session Management

```php
// Quiz session validation
function validateQuizSession($attemptId) {
    $attempt = QuizAttempt::find($attemptId);
    
    // Check time limit
    if ($attempt->quiz->time_limit_minutes > 0) {
        $timeSpent = now()->diffInMinutes($attempt->started_at);
        if ($timeSpent >= $attempt->quiz->time_limit_minutes) {
            autoSubmitQuiz($attemptId);
            return false;
        }
    }
    
    // Check IP consistency
    $currentIp = request()->ip();
    if ($attempt->ip_address !== $currentIp) {
        logSecurityEvent($attemptId, 'IP_CHANGE', [
            'original' => $attempt->ip_address,
            'current' => $currentIp
        ]);
    }
    
    return true;
}
```

### 6.3 Authentication & Authorization

- Laravel Sanctum untuk API authentication
- Role-based access control (RBAC)
- Permission checks untuk setiap action
- CSRF protection
- XSS sanitization

### 6.4 Data Security

- Encrypted storage untuk sensitive data
- SSL/TLS untuk all connections
- Input validation dan sanitization
- SQL injection prevention (Eloquent ORM)

---

## 7. User Interface Wireframes

### 7.1 Halaman Utama Kursus (Student Dashboard)

```
┌─────────────────────────────────────────────────────────────────┐
│  🎓 YayasanEdu                    🔔  👤 Profile ▼             │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  My Courses                                              │  │
│  │  ─────────────────────────────────────────────────────  │  │
│  │                                                          │  │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  │  │
│  │  │ 📚       │  │ 📚       │  │ 📚       │  │ 📚       │  │  │
│  │  │          │  │          │  │          │  │          │  │  │
│  │  │ Course 1 │  │ Course 2 │  │ Course 3 │  │ Course 4 │  │  │
│  │  │ ████░░░░ │  │ ██████░░ │  │ ████░░░░ │  │ ████████ │  │  │
│  │  │ 40%      │  │ 75%      │  │ 40%      │  │ 100%     │  │  │
│  │  │          │  │          │  │          │  │ ✅       │  │  │
│  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘  │  │
│  │                                                          │  │
│  │  [Browse More Courses]                                  │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────┐  ┌──────────────────────────────┐   │
│  │  📊 My Progress      │  │  📅 Upcoming Deadlines      │   │
│  │  ──────────────────  │  │  ──────────────────────────  │   │
│  │  Courses: 4          │  │  • Quiz #1 - Tomorrow       │   │
│  │  Completed: 1        │  │  • Assignment - 3 days      │   │
│  │  In Progress: 3      │  │  • Final Exam - 1 week      │   │
│  │  Hours: 24           │  │                              │   │
│  └──────────────────────┘  └──────────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 7.2 Halaman Detail Kursus

```
┌─────────────────────────────────────────────────────────────────┐
│  ← Back                                      🔔  👤 Profile ▼   │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  [Thumbnail Image]                                      │  │
│  │                                                          │  │
│  │  Bahasa Inggris Dasar                                   │  │
│  │  ─────────────────────                                   │  │
│  │  📚 8 Modules  │  ⏱️ 20 hours  │  🎓 156 students        │  │
│  │                                                          │  │
│  │  [Enroll Now] [Start Learning] [Share]                 │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────┬─────────────┬─────────────┐                  │
│  │ 📖 Overview │ 📝 Curriculum│ ★ Reviews   │                  │
│  └─────────────┴─────────────┴─────────────┘                  │
│                                                                 │
│  Description:                                                  │
│  Kursus Bahasa Inggris untuk pemula dengan metode...</         │
│                                                                 │
│  What you'll learn:                                            │
│  ✓ Basic vocabulary                                            │
│  ✓ Grammar fundamentals                                        │
│  ✓ Speaking practice                                           │
│  ✓ Writing skills                                              │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  Course Content                                         │  │
│  │  ─────────────────────────────────────────────────────  │  │
│  │                                                          │  │
│  │  ▼ Module 1: Introduction (2 lessons)                   │  │
│  │    □ 1.1 Welcome to the Course [10 min]                 │  │
│  │    □ 1.2 Course Overview [15 min]                       │  │
│  │                                                          │  │
│  │  ▼ Module 2: Basic Vocabulary (5 lessons)              │  │
│  │    □ 2.1 Numbers [20 min]                               │  │
│  │    □ 2.2 Colors [15 min]                                │  │
│  │    ...                                                  │  │
│  │                                                          │  │
│  │  ▼ Module 3: Grammar (4 lessons + 1 Quiz)              │  │
│  │    ...                                                  │  │
│  │    📝 3.5 Quiz: Basic Grammar [30 min]                  │  │
│  │                                                          │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 7.3 Halaman Quiz

```
┌─────────────────────────────────────────────────────────────────┐
│  Quiz: Basic Grammar                           ⏱️ 25:43 ⏸️      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Question 5 of 20                                    ☐ Flag     │
│  ─────────────────────────────────────────────────────────────  │
│                                                                 │
│  Which sentence is correct?                                    │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  ○ A) She go to school every day                        │  │
│  │  ○ B) She goes to school every day  ✓ Correct          │  │
│  │  ○ C) She going to school every day                      │  │
│  │  ○ D) She gone to school every day                      │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  [Previous]                        [Next]  [Submit Quiz]      │
│                                                                 │
│  ┌────┬────┬────┬────┬────┬────┬────┬────┬────┬────┐          │
│  │ 1  │ 2  │ 3  │ 4  │ 5● │ 6  │ 7  │ 8  │ 9  │ 10 │          │
│  ├────┼────┼────┼────┼────┼────┼────┼────┼────┼────┤          │
│  │ 11 │ 12 │ 13 │ 14 │ 15 │ 16 │ 17 │ 18 │ 19 │ 20 │          │
│  └────┴────┴────┴────┴────┴────┴────┴────┴────┴────┘          │
│                                                                 │
│  Legend: ● Answered  ○ Not Answered  ⚑ Flagged                │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 7.4 Halaman Hasil Quiz

```
┌─────────────────────────────────────────────────────────────────┐
│                              🔔  👤 Profile ▼                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │              🎉 Congratulations!                        │  │
│  │                                                          │  │
│  │                   85%                                    │  │
│  │                                                          │  │
│  │           ✓ Passed                                       │  │
│  │           Score: 85/100                                 │  │
│  │           Time: 22:15                                   │  │
│  │                                                          │  │
│  │  [Retake Quiz]  [View Answers]  [Download Certificate]  │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  Question Breakdown                                     │  │
│  │  ─────────────────────────────────────────────────────  │  │
│  │                                                          │  │
│  │  1. ✓ Correct                     6. ✗ Incorrect        │  │
│  │  2. ✓ Correct                     7. ✓ Correct          │  │
│  │  3. ✓ Correct                     8. ✗ Incorrect        │  │
│  │  4. ✓ Correct                     9. ✓ Correct          │  │
│  │  5. ✓ Correct                    10. ✓ Correct         │  │
│  │                                                          │  │
│  │  ...                                                    │  │
│  │                                                          │  │
│  │  Correct: 17/20 (85%)                                  │  │
│  │  Incorrect: 3/20 (15%)                                 │  │
│  │                                                          │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 7.5 Dashboard Admin

```
┌─────────────────────────────────────────────────────────────────┐
│  Admin Panel                              🔔  👤 Admin ▼       │
├────────┬────────────────────────────────────────────────────────┤
│        │                                                        │
│ 📊     │  Overview                                              │
│ Dash-  │  ───────────────────────────────────────────────────── │
│ board  │                                                        │
│        │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐      │
│ 📚     │  │ 156     │ │ 42      │ │ 89%     │ │ 4.2     │      │
│ Courses│  │ Students│ │ Courses │ │ Pass    │ │ Avg     │      │
│        │  │         │ │         │ │ Rate    │ │ Score   │      │
│ 📝     │  └─────────┘ └─────────┘ └─────────┘ └─────────┘      │
│ Quiz   │                                                        │
│        │  ┌────────────────────────────┐ ┌──────────────────┐  │
│ 📈     │  │ Recent Enrollments         │ │ Top Courses      │  │
│ Reports│  │ ──────────────────────────  │ │ ────────────────  │  │
│        │  │ John D. - Math 101         │ │ 1. English Basic │  │
│ 🎓     │  │ Jane S. - Science          │ │ 2. Math Premium  │  │
│ Certi- │  │ Mike T. - History          │ │ 3. Physics       │  │
│ ficate │  │ ...                        │ │                  │  │
│        │  └────────────────────────────┘ └──────────────────┘  │
│ ⚙️     │                                                        │
│ Settings                                                     │
│        │                                                        │
└────────┴────────────────────────────────────────────────────────┘
```

### 7.6 Admin - Course Builder

```
┌─────────────────────────────────────────────────────────────────┐
│  Create Course                              🔔  👤 Admin ▼     │
├────────┬────────────────────────────────────────────────────────┤
│        │                                                        │
│ 📚     │  ┌─────────────────────────────────────────────────┐  │
│ Courses│  │ Title: [________________________]                │  │
│        │  │                                                 │  │
│        │  │ Description:                                   │  │
│        │  │ [                                         ]     │  │
│        │  │ [                                         ]     │  │
│        │  │                                                 │  │
│        │  │ Category: [Select Category____▼]                │  │
│        │  │ Difficulty: [Beginner____▼]                     │  │
│        │  │                                                 │  │
│        │  │ Price: [$0.00]  ✓ Free Course                   │  │
│        │  │                                                 │  │
│        │  │ [Thumbnail Upload]                              │  │
│        │  │                                                 │  │
│        │  │ [Save Draft]  [Publish]                         │  │
│        │  └─────────────────────────────────────────────────┘  │
│        │                                                        │
│        │  ┌─────────────────────────────────────────────────┐  │
│        │  │ Course Modules                          [+ Add]  │  │
│        │  │ ─────────────────────────────────────────────────│  │
│        │  │                                                 │  │
│        │  │ ⊞ Module 1: Getting Started            [Edit] ✕│  │
│        │  │   └─ Lesson 1.1: Welcome                    👁   │  │
│        │  │   └─ Lesson 1.2: How to Use                  👁   │  │
│        │  │                                                 │  │
│        │  │ ⊞ Module 2: Core Concepts              [Edit] ✕│  │
│        │  │   └─ Lesson 2.1: ...                        👁   │  │
│        │  │   └─ Quiz 2.1: Test Your Knowledge         📝   │  │
│        │  │                                                 │  │
│        │  └─────────────────────────────────────────────────┘  │
│        │                                                        │
└────────┴────────────────────────────────────────────────────────┘
```

---

## 8. User Workflow

### 8.1 Alur Kerja Lengkap

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                        CBT USER WORKFLOW                                    │
└──────────────────────────────────────────────────────────────────────────────┘

    ┌─────────────┐
    │  USER       │
    │  REGISTRA-  │
    │  TION       │
    └──────┬──────┘
           │
           ▼
    ┌─────────────────────────────────────────────────────────────────┐
    │ 1. LOGIN / AUTHENTICATION                                      │
    │    - User login ke platform                                     │
    │    - Role: Student, Teacher, Admin                             │
    └──────┬──────────────────────────────────────────────────────────┘
           │
           ▼
    ┌─────────────────────────────────────────────────────────────────┐
    │ 2. BROWSE COURSE CATALOG                                       │
    │    - View all available courses                                 │
    │    - Filter by category, difficulty, price                     │
    │    - View course details (description, curriculum, reviews)    │
    └──────┬──────────────────────────────────────────────────────────┘
           │
           ▼
    ┌─────────────────────────────────────────────────────────────────┐
    │ 3. ENROLL IN COURSE                                            │
    │    - Click "Enroll" or "Start Learning"                         │
    │    - Check enrollment requirements (prerequisites)            │
    │    - For paid courses: process payment                         │
    │    - Create enrollment record                                   │
    └──────┬──────────────────────────────────────────────────────────┘
           │
           ▼
    ┌─────────────────────────────────────────────────────────────────┐
    │ 4. LEARN (Loop through modules & lessons)                      │
    │    - View lesson content (video/text/document)                  │
    │    - Track time spent                                           │
    │    - Mark lesson as complete                                    │
    │    - Progress auto-saved                                         │
    │                                                                  │
    │    ┌────────────────────────────────────────────────────────┐   │
    │    │ 4a. QUIZ (Optional - when reaching quiz lesson)        │   │
    │    │    - Start quiz attempt                                │   │
    │    │    - Answer questions (with timer if enabled)         │   │
    │    │    - Auto-submit on timeout                            │   │
    │    │    - Submit manually                                    │   │
    │    │    - Auto-grade objective questions                    │   │
    │    │    - Queue essay for manual grading                     │   │
    │    │    - View results & feedback                            │   │
    │    └────────────────────────────────────────────────────────┘   │
    │                                                                  │
    └──────┬──────────────────────────────────────────────────────────┘
           │
           ▼
    ┌─────────────────────────────────────────────────────────────────┐
    │ 5. COMPLETE COURSE                                             │
    │    - All lessons marked as complete                            │
    │    - All required quizzes passed                                │
    │    - Course progress = 100%                                     │
    │    - Update enrollment status to 'completed'                   │
    └──────┬──────────────────────────────────────────────────────────┘
           │
           ▼
    ┌─────────────────────────────────────────────────────────────────┐
    │ 6. GET CERTIFICATE                                             │
    │    - Check if course has certificate                           │
    │    - Verify passing score met                                   │
    │    - Generate unique certificate                                │
    │    - Issue certificate record                                    │
    │    - Enable download                                             │
    └──────┬──────────────────────────────────────────────────────────┘
           │
           ▼
    ┌─────────────────────────────────────────────────────────────────┐
    │ 7. VIEW & DOWNLOAD CERTIFICATE                                  │
    │    - View certificate in dashboard                              │
    │    - Download PDF certificate                                   │
    │    - Share on social media                                      │
    │    - Verify via public verification page                        │
    └─────────────────────────────────────────────────────────────────┘
```

### 8.2 Admin Workflow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        CBT ADMIN WORKFLOW                                   │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌─────────────┐
    │  ADMIN      │
    │  LOGIN      │
    └──────┬──────┘
           │
           ▼
    ┌──────────────────────────────────────────────────────────────────┐
    │ 1. DASHBOARD                                                      │
    │    - View overview statistics                                     │
    │    - Monitor recent activities                                    │
    └──────┬───────────────────────────────────────────────────────────┘
           │
     ┌─────┴───────────────────────────────────────────────────────────┐
     │                                                                 │
     ▼                                                                 ▼
┌─────────────────────┐                                    ┌─────────────────────┐
│ 2a. COURSE MANAGEMENT│                                   │ 2b. QUIZ MANAGEMENT │
│ - Create course     │                                    │ - Create quiz       │
│ - Edit course       │                                    │ - Add questions     │
│ - Delete course     │                                    │ - Configure settings│
│ - Manage modules    │                                    │ - Set time/attempts │
│ - Manage lessons    │                                    │ - Grade essays      │
│ - Publish/unpublish │                                    │ - View analytics    │
└─────────────────────┘                                    └─────────┬───────────┘
                                                                  │
                                                                  ▼
┌─────────────────────┐                                    ┌─────────────────────┐
│ 2c. CERTIFICATE     │                                   │ 2d. ANALYTICS        │
│ - Create template   │                                   │ - View reports      │
│ - Design template   │                                   │ - Export data       │
│ - Issue manual cert │                                   │ - Student progress  │
│ - Verify certificates│                                  │ - Quiz performance  │
└─────────────────────┘                                   └─────────────────────┘
```

---

## 9. Technology Stack

### 9.1 Core Technology

| Layer | Technology | Version |
|-------|------------|---------|
| Backend | Laravel | 10.x+ |
| Frontend | Blade + Alpine.js | Latest |
| CSS | Tailwind CSS | 3.x |
| JavaScript | Vanilla JS + Vue.js (optional) | ES6+ |
| Database | MySQL/MariaDB | 8.0+ |
| Cache | Redis | 7.x |
| Queue | Laravel Queue (Redis) | - |

### 9.2 Additional Libraries

| Purpose | Library |
|---------|---------|
| PDF Generation | barryvdh/laravel-dompdf |
| Excel Import/Export | maatwebsite/excel |
| Charts/Analytics | Chart.js / ApexCharts |
| Video Player | Plyr.js |
| Rich Text Editor | TinyMCE / Quill |
| Drag & Drop | Sortable.js |
| Date/Time | Carbon |
| API Documentation | Swagger/OpenAPI |

### 9.3 API Standards

- RESTful API design
- JSON responses
- Bearer token authentication (Laravel Sanctum)
- Rate limiting
- API versioning (/api/v1/)

---

## 10. Plugin Structure

### 10.1 Directory Structure

```
plugins/cbt/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── CbtInstallCommand.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── CourseController.php
│   │   │   │   ├── ModuleController.php
│   │   │   │   ├── LessonController.php
│   │   │   │   ├── QuizController.php
│   │   │   │   ├── QuestionController.php
│   │   │   │   ├── CertificateController.php
│   │   │   │   └── AnalyticsController.php
│   │   │   └── Api/
│   │   │       ├── CourseApiController.php
│   │   │       ├── QuizApiController.php
│   │   │       └── CertificateApiController.php
│   │   ├── Middleware/
│   │   │   ├── QuizSessionMiddleware.php
│   │   │   └── AntiCheatMiddleware.php
│   │   └── Requests/
│   │       ├── CourseRequest.php
│   │       ├── QuizRequest.php
│   │       └── QuestionRequest.php
│   ├── Models/
│   │   ├── Course.php
│   │   ├── Module.php
│   │   ├── Lesson.php
│   │   ├── Quiz.php
│   │   ├── Question.php
│   │   ├── Answer.php
│   │   ├── Enrollment.php
│   │   ├── LessonProgress.php
│   │   ├── QuizAttempt.php
│   │   ├── QuizAnswer.php
│   │   ├── Result.php
│   │   ├── Certificate.php
│   │   ├── CertificateIssued.php
│   │   └── CourseCategory.php
│   ├── Services/
│   │   ├── CourseService.php
│   │   ├── QuizService.php
│   │   ├── GradingService.php
│   │   ├── CertificateService.php
│   │   ├── ProgressService.php
│   │   ├── AnalyticsService.php
│   │   ├── ImportExportService.php
│   │   └── AntiCheatService.php
│   └── Events/
│       ├── CourseCompleted.php
│       ├── QuizSubmitted.php
│       └── CertificateIssued.php
│
├── database/
│   └── migrations/
│       ├── 2024_01_01_000001_create_cbt_categories_table.php
│       ├── 2024_01_01_000002_create_cbt_courses_table.php
│       ├── 2024_01_01_000003_create_cbt_modules_table.php
│       ├── 2024_01_01_000004_create_cbt_lessons_table.php
│       ├── 2024_01_01_000005_create_cbt_quizzes_table.php
│       ├── 2024_01_01_000006_create_cbt_questions_table.php
│       ├── 2024_01_01_000007_create_cbt_answers_table.php
│       ├── 2024_01_01_000008_create_cbt_enrollments_table.php
│       ├── 2024_01_01_000009_create_cbt_lesson_progress_table.php
│       ├── 2024_01_01_000010_create_cbt_quiz_attempts_table.php
│       ├── 2024_01_01_000011_create_cbt_quiz_answers_table.php
│       ├── 2024_01_01_000012_create_cbt_results_table.php
│       ├── 2024_01_01_000013_create_cbt_certificates_table.php
│       └── 2024_01_01_000014_create_cbt_certificate_issued_table.php
│
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   │   ├── courses/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── quizzes/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── grading.blade.php
│   │   │   ├── analytics/
│   │   │   │   └── index.blade.php
│   │   │   └── certificates/
│   │   │       ├── index.blade.php
│   │   │       └── templates.blade.php
│   │   ├── student/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── courses/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── show.blade.php
│   │   │   │   └── learn.blade.php
│   │   │   ├── quizzes/
│   │   │   │   ├── start.blade.php
│   │   │   │   ├── take.blade.php
│   │   │   │   └── result.blade.php
│   │   │   └── certificates/
│   │   │       ├── index.blade.php
│   │   │       └── view.blade.php
│   │   └── layouts/
│   │       ├── admin.blade.php
│   │       └── student.blade.php
│   │
│   ├── js/
│   │   ├── components/
│   │   │   ├── QuizTimer.vue
│   │   │   ├── QuestionCard.vue
│   │   │   ├── DragDrop.vue
│   │   │   ├── Matching.vue
│   │   │   └── ProgressBar.vue
│   │   ├── quiz.js
│   │   ├── course-builder.js
│   │   └── analytics.js
│   │
│   └── css/
│       └── cbt.css
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── tests/
│   ├── Feature/
│   │   ├── CourseTest.php
│   │   ├── QuizTest.php
│   │   └── GradingTest.php
│   └── Unit/
│       ├── ProgressCalculationTest.php
│       └── CertificateGenerationTest.php
│
├── config/
│   └── cbt.php
│
├── composer.json
├── package.json
├── README.md
└── plugin.json
```

### 10.2 Service Providers

```php
// CbtServiceProvider.php
namespace Cbt\Providers;

use Illuminate\Support\ServiceProvider;
use Cbt\Services\CourseService;
use Cbt\Services\QuizService;
use Cbt\Services\GradingService;

class CbtServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register services
        $this->app->singleton(CourseService::class);
        $this->app->singleton(QuizService::class);
        $this->app->singleton(GradingService::class);
        
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Cbt\Console\Commands\CbtInstallCommand::class,
            ]);
        }
    }

    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cbt');
        
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/cbt.php' => config_path('cbt.php'),
        ]);
        
        // Register event listeners
        $this->app['events']->listen(
            \Cbt\Events\QuizSubmitted::class,
            \Cbt\Listeners\GradeQuizListener::class
        );
    }
}
```

### 10.3 Configuration (config/cbt.php)

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Quiz Settings
    |--------------------------------------------------------------------------
    */
    'quiz' => [
        'default_time_limit' => env('CBT_QUIZ_TIME_LIMIT', 30), // minutes
        'default_attempts' => env('CBT_QUIZ_ATTEMPTS', 3),
        'default_passing_score' => env('CBT_QUIZ_PASSING_SCORE', 70),
        'enable_timer' => env('CBT_QUIZ_TIMER_ENABLED', true),
        'enable_anti_cheat' => env('CBT_ANTI_CHEAT_ENABLED', true),
        'shuffle_questions_default' => false,
        'shuffle_answers_default' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Certificate Settings
    |--------------------------------------------------------------------------
    */
    'certificate' => [
        'default_validity_days' => env('CBT_CERTIFICATE_VALIDITY', 0), // 0 = permanent
        'enable_verification' => true,
        'default_template' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Progress Settings
    |--------------------------------------------------------------------------
    */
    'progress' => [
        'video_completion_threshold' => 90, // percentage watched
        'track_time_spent' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Import/Export Settings
    |--------------------------------------------------------------------------
    */
    'import_export' => [
        'max_file_size' => env('CBT_MAX_IMPORT_SIZE', 10485760), // 10MB
        'allowed_extensions' => ['csv', 'xlsx', 'json'],
    ],
];
```

---

## 11. Implementation Checklist

### Phase 1: Core Foundation
- [ ] Database migrations
- [ ] Basic models and relationships
- [ ] Course CRUD operations
- [ ] Module management
- [ ] Lesson content types

### Phase 2: Quiz System
- [ ] Quiz creation and management
- [ ] Question types implementation
- [ ] Quiz taking interface
- [ ] Timer functionality
- [ ] Auto-grading for objective questions

### Phase 3: Progress & Results
- [ ] Lesson progress tracking
- [ ] Course progress calculation
- [ ] Quiz results display
- [ ] Manual grading interface
- [ ] Grade book

### Phase 4: Certificates
- [ ] Certificate template builder
- [ ] Auto-issuance on completion
- [ ] PDF generation
- [ ] Verification system

### Phase 5: Analytics & Reports
- [ ] Dashboard statistics
- [ ] Course analytics
- [ ] Quiz analytics
- [ ] Export capabilities

### Phase 6: Advanced Features
- [ ] Anti-cheat system
- [ ] Import/Export quiz
- [ ] API development
- [ ] Mobile optimization

---

## 12. Conclusion

Dokumen ini memberikan rancangan lengkap untuk CBT Plugin yang:

1. **Modular**: Dapat diinstall/uninstall tanpa mempengaruhi sistem utama
2. **Scalable**: Mendukung banyak pengguna secara bersamaan
3. **Secure**: Dilengkapi dengan sistem anti-cheat dan keamanan data
4. **User-friendly**: Interface yang intuitif untuk admin dan student
5. **Extensible**: Dapat dikembangkan dengan fitur tambahan

Implementasi dapat dimulai dari Phase 1 dan dilanjutkan secara iteratif sesuai prioritas dan kebutuhan.
