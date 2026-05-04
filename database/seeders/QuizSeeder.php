<?php

namespace Database\Seeders;

use App\Models\MyQuiz;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ambil user pertama yang ada
        $users = DB::table('users')
            ->orderBy('id_user')
            ->pluck('id_user')
            ->toArray();

        if (empty($users)) {
            throw new \Exception('Harus ada user untuk QuizSeeder');
        }

        // ambil reference data (ambil random nanti)
        $majorIds = DB::table('majors')->pluck('id_major')->toArray();
        $courseIds = DB::table('courses')->pluck('id_course')->toArray();
        $lecturerIds = DB::table('lecturers')->pluck('id_lecturer')->toArray();

        $titles = [
            'Quiz Laravel Dasar', 'Pemrograman Web Lanjut', 'Struktur Data Quiz', 'Database Design Basics',
            'Algoritma & Kompleksitas', 'Frontend Development', 'Backend API Design', 'OOP Concept Quiz',
            'Software Engineering', 'Fullstack Challenge', 'Networking Essentials', 'Cyber Security Intro',
            'Mobile App Development', 'UI/UX Design Principles', 'Cloud Computing Basics', 'Artificial Intelligence',
            'Machine Learning 101', 'Data Science Methods', 'Project Management', 'Human Computer Interaction',
            'Introduction to Python', 'JavaScript Mastery', 'React.js Deep Dive', 'Vue.js Basics',
            'Node.js Essentials', 'Go Programming', 'Rust for Systems', 'C++ Advanced',
            'SQL Optimization', 'NoSQL Databases', 'Docker & Kubernetes', 'AWS Fundamentals',
            'Azure Solutions', 'Google Cloud Platform', 'Microservices Architecture', 'System Design',
            'Cryptography Basics', 'Ethical Hacking', 'Digital Forensics', 'Computer Vision',
            'Natural Language Processing', 'Robotics Intro', 'Blockchain Essentials', 'IoT Foundations',
            'Embedded Systems', 'Compilers & Interpreters', 'Operating Systems', 'Computer Graphics',
            'Game Development Basics', 'VR & AR Intro',
            'Advanced Laravel Patterns', 'Docker for Developers', 'Kubernetes Orchestration', 'Microservices with Go',
            'React Native vs Flutter', 'SwiftUI Mastery', 'Kotlin for Android', 'Java Spring Boot Deep Dive',
            'PostgreSQL Performance', 'Redis Caching Strategies', 'Elasticsearch for Data', 'GraphQL vs REST',
            'OAuth2 & OpenID Connect', 'JWT Security Best Practices', 'Penetration Testing Intro', 'Network Protocols',
            'TCP/IP Fundamentals', 'Linux System Admin', 'Bash Scripting', 'Python for Automation',
            'TensorFlow Basics', 'PyTorch for Deep Learning', 'Scikit-Learn Intro', 'Matplotlib & Seaborn',
            'Pandas for Data Analysis', 'Apache Spark for Big Data', 'Kafka Event Streaming', 'RabbitMQ Essentials',
            'Serverless with AWS Lambda', 'Terraform IaC', 'Ansible Configuration', 'CI/CD with GitHub Actions',
            'Unit Testing in PHP', 'Integration Testing', 'Agile Methodology', 'Scrum Master Roles',
            'Clean Code Principles', 'Refactoring Techniques', 'Domain Driven Design', 'SOLID Principles',
            'Design Patterns in Java', 'Concurrency in C++', 'Embedded C Programming', 'Arduino Robotics',
            'Raspberry Pi Projects', 'IoT Security', 'Wireless Sensor Networks', 'FPGA Design with VHDL'
        ];

        $tagPool = ['coding', 'web', 'database', 'design', 'security', 'network', 'ai', 'cloud', 'mobile', 'devops', 'backend', 'frontend', 'data', 'architecture'];

        $baseDate = now()->subDays(60);

        foreach ($titles as $index => $title) {
            $createdAt = $baseDate->copy()
                ->addDays($index)
                ->setTime(rand(8, 22), rand(0, 59), rand(0, 59));

            $authorId = $users[array_rand($users)];
            $userFolders = DB::table('folders')->where('user_id', $authorId)->pluck('id_folder')->toArray();

            $quiz = MyQuiz::create([
                'author_id' => $authorId,
                'title' => $title,
                'description' => 'A comprehensive quiz exploring ' . $title . '. Test your knowledge on core concepts and advanced topics with our curated questions.',
                'major_id' => $this->randomOrNull($majorIds),
                'course_id' => $this->randomOrNull($courseIds),
                'lecturer_id' => $this->randomOrNull($lecturerIds),
                'folder_id' => $this->randomOrNull($userFolders),
                'time_limit_minutes' => rand(1, 10) > 3 ? [15, 30, 45, 60, 90][array_rand([15, 30, 45, 60, 90])] : null,
                'visibility' => 'published',
                'access' => 'public',
                'allow_copy' => true,
                'version_number' => 1,
                'has_been_updated' => false,
                'cover_image_url' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            
            // Sync 2-4 random tags
            $randomTagKeys = array_rand(array_flip($tagPool), rand(2, 4));
            $randomTags = is_array($randomTagKeys) ? $randomTagKeys : [$randomTagKeys];
            
            $tagIds = [];
            foreach ($randomTags as $tagName) {
                $tagIds[] = Tag::firstOrCreate(['name' => $tagName])->id_tag;
            }
            $quiz->tags()->sync($tagIds);
        }
    }

    private function randomOrNull(array $data)
    {
        return empty($data) ? null : $data[array_rand($data)];
    }
}