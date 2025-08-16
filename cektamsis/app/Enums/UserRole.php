<?php

namespace App\Enums;

enum UserRole: string
{

    case Siswa = 'user';
    case Admin = 'admin';
    case Guru = 'guru';
}
