<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DevDojo\Chatter\Models\Discussion as ChatterDiscussion;
use DevDojo\Chatter\Models\Post;

class Discussion extends ChatterDiscussion
{

    public function lastPost()
    {
        return $this->hasOne(Post::class, 'chatter_discussion_id')->orderBy('created_at', 'ASC');
    }

}
