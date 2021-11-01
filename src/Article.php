<?php

namespace BluaBlue;

class Article
{

    private ?string $id;
    private ?string $name = null;
    private string $slug;
    private ?string $teaser = 'A new article';
    private ?string $image_id = null;
    private string $author_user_id;
    private ?string $category_id = null;
    private int $is_public;
    private ?string $keywords = null;
    private ?string $publish_date = null;
    private string $insert_date;
    private ?string $update_date = null;
    private ?string $delete_date = null;
    private array $article_comment = [];
    private array $article_content = [];
    private array $article_rating = [];
    private array $article_store = [];

    use Helper;

    public function getContentHtml()
    {
        $html = '';
        foreach ($this->getArticleContent() as $content){
            if(isset($content['html'])){
                $html .= $content['html'];
            }
        }
        return $html;
    }

    public function getId(): mixed
    {
        return $this->id;
    }


    public function getName(): mixed
    {
        return $this->name;
    }

    public function setName($input): static
    {
        $this->name = $input;
        return $this;
    }

    public function getSlug(): mixed
    {
        return $this->slug;
    }

    public function getTeaser(): mixed
    {
        return $this->teaser;
    }

    public function setTeaser($input): static
    {
        $this->teaser = $input;
        return $this;
    }

    public function getImageId(): mixed
    {
        return $this->image_id;
    }

    public function setImageId($input): static
    {
        $this->image_id = $input;
        return $this;
    }

    public function getAuthorUserId(): mixed
    {
        return $this->author_user_id;
    }

    public function setAuthorUserId($input): static
    {
        $this->author_user_id = $input;
        return $this;
    }

    public function getCategoryId(): mixed
    {
        return $this->category_id;
    }

    public function setCategoryId($input): static
    {
        $this->category_id = $input;
        return $this;
    }

    public function getIsPublic(): mixed
    {
        return $this->is_public;
    }

    public function setIsPublic($input): static
    {
        $this->is_public = $input;
        return $this;
    }

    public function getKeywords(): mixed
    {
        return $this->keywords;
    }

    public function setKeywords($input): static
    {
        $this->keywords = $input;
        return $this;
    }

    public function getPublishDate(): mixed
    {
        return $this->publish_date;
    }

    public function setPublishDate($input): static
    {
        $this->publish_date = $input;
        return $this;
    }

    public function getInsertDate(): mixed
    {
        return $this->insert_date;
    }

    public function getUpdateDate(): mixed
    {
        return $this->update_date;
    }

    public function setUpdateDate($input): static
    {
        $this->update_date = $input;
        return $this;
    }

    public function getDeleteDate(): mixed
    {
        return $this->delete_date;
    }

    public function setDeleteDate($input): static
    {
        $this->delete_date = $input;
        return $this;
    }

    public function getArticleComment(): array
    {
        return $this->article_comment;
    }

    public function addArticleComment(array $newSub): static
    {
        $this->article_comment[] = $newSub;
        return $this;
    }

    public function removeArticleComment(string $id): static
    {
        foreach ($this->article_comment as $i => $any){
            if($any['id'] === $id){
                $this->article_comment[$i]['delete_date'] = '.';
            }
        }
        return $this;
    }

    public function getArticleContent(): array
    {
        return $this->article_content;
    }

    public function addArticleContent(array $newSub): static
    {
        $this->article_content[] = $newSub;
        return $this;
    }

    public function removeArticleContent(string $id): static
    {
        foreach ($this->article_content as $i => $any){
            if($any['id'] === $id){
                $this->article_content[$i]['delete_date'] = '.';
            }
        }
        return $this;
    }

    public function getArticleRating(): array
    {
        return $this->article_rating;
    }

    public function addArticleRating(array $newSub): static
    {
        $this->article_rating[] = $newSub;
        return $this;
    }

    public function removeArticleRating(string $id): static
    {
        foreach ($this->article_rating as $i => $any){
            if($any['id'] === $id){
                $this->article_rating[$i]['delete_date'] = '.';
            }
        }
        return $this;
    }

    public function getArticleStore(): array
    {
        return $this->article_store;
    }

    public function addArticleStore(array $newSub): static
    {
        $this->article_store[] = $newSub;
        return $this;
    }

    public function removeArticleStore(string $id): static
    {
        foreach ($this->article_store as $i => $any){
            if($any['id'] === $id){
                $this->article_store[$i]['delete_date'] = '.';
            }
        }
        return $this;
    }

}