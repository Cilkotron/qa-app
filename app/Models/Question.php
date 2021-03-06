<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Http\Traits\VotableTrait;
// use Purifier;



class Question extends Model
{
    use HasFactory;
    use VotableTrait;

    protected $fillable = ['title', 'body', 'slug'];
    protected $appends = ['created_date', 'is_favorite', 'favorites_count', 'body_html'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function setTitleAttributes($value) {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getUrlAttribute()
    {
        return route('question.show', $this->slug);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if($this->answers_count > 0) {
            if($this->best_answer_id) {
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute()
    {
        return clean($this->bodyHtml());
    }

    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('votes_count', 'DESC');
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }
    public function getIsFavoriteAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }

    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }
    public function excerpt($lenght)
    {
        return str_limit(strip_tags($this->bodyHtml()), $lenght);
    }

    private function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }

}
