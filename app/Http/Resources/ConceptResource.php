<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConceptResource extends JsonResource
{
    /**
     * Transform the concept into a JSON-serializable array.
     * whenLoaded() includes the relation only if it was eager-loaded — avoids N+1.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'explanation'   => $this->explanation,
            'code_example'  => $this->code_example,
            'code_language' => $this->code_language,
            'phase'         => $this->phase,
            'is_published'  => $this->is_published,
            'view_count'    => $this->view_count,
            'category'      => new CategoryResource($this->whenLoaded('category')),
            'examples'      => ExampleResource::collection($this->whenLoaded('examples')),
            'tags'          => TagResource::collection($this->whenLoaded('tags')),
            'author'        => new UserResource($this->whenLoaded('author')),
            'created_at'    => $this->created_at->toISOString(),
            'updated_at'    => $this->updated_at->toISOString(),
        ];
    }
}
