USE student_management; 

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE, -- اسم المستخدم يجب أن يكون فريدًا
    student_name VARCHAR(255), -- اسم الطالب
    specialization ENUM('IT', 'ميكاترونكس', 'أوتوترونكس', 'طاقه متجددة', 'اطراف صناعية'), -- التخصص
    academic_year ENUM('الأولى', 'الثانية', 'الثالثة', 'الرابعة'), -- السنة الدراسية
    email VARCHAR(255) UNIQUE, -- البريد الإلكتروني يجب أن يكون فريدًا
    password VARCHAR(255), -- كلمة المرور
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- وقت إنشاء الحساب
);
