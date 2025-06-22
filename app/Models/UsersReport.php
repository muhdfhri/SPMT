<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ReportAttachment;

class UsersReport extends Model
{
    use HasFactory;

    protected $table = 'users_reports';
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'status', 'admin_notes', 'admin_id', 'resolved_at'
    ];

    protected $dates = [
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(ReportAttachment::class, 'users_report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}