<?php
namespace src\Services;

class PaginatorService
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $items_current_page;

    /**
     * @var int
     */
    private $last_page;

    /**
     * @var int
     */
    private $next_page;

    /**
     * @var int
     */
    private $current_page;

    /**
     * @var int
     */
    private $total_pages;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $pages;

    /**
     * @var array
     */
    private $paginator;

    /**
     * @var int|null
     */
    private $from;

    /**
     * @var int|null
     */
    private $to;

    public function __construct(array $data = [], int $offset, int $limit = 40)
    {
        $this->data = $data;
        $this->limit = $limit < 1 ? 1 : $limit;
        $this->offset = $offset < 1 ? 1 : $offset;
        $this->items_current_page = 0;
        $this->pages = [];
        $this->total_pages = 0;
        $this->last_page = 0;
        $this->next_page = 0;
        $this->current_page = 0;
        $this->paginator = [];
        $this->count = count($this->data);
        $this->paginate();
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getItemsCurrentPage(): int
    {
        return $this->items_current_page;
    }

    public function getLastPage(): int
    {
        return $this->last_page;
    }

    public function getNextPage(): int
    {
        return $this->next_page;
    }

    public function getCurrentPage(): int
    {
        return $this->current_page;
    }

    public function getTotalPages(): int
    {
        return $this->total_pages;
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    public function getPaginator(): array
    {
        return $this->paginator;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function getTo(): ?int
    {
        return $this->to;
    }

    private function paginate()
    {
        if($this->count !== 0) {
            $this->pages = array_chunk($this->data, $this->limit);
            $this->total_pages = count($this->pages);
            $this->last_page = $this->offset - 1 < 1 ? $this->offset : $this->offset - 1;
            $this->next_page = $this->offset + 1 > $this->total_pages ? $this->offset : $this->offset + 1;
            $this->current_page = $this->offset > $this->total_pages ? $this->total_pages : $this->offset;

            $this->paginator = $this->current_page > $this->total_pages ? [] : $this->pages[$this->current_page - 1];

            $this->items_current_page = count($this->paginator);

            if($this->offset <= 1) {
                $this->from = 1;
                $this->to = $this->items_current_page;
            } else {
                $this->from = (($this->offset - 1) * $this->limit) + 1;
                $this->to = (($this->offset - 1) * $this->limit) + $this->items_current_page;
            }
        }
    }
}