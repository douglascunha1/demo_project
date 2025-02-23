<?php

namespace Src\Models;

/**
 * Represents a user filter
 *
 * Class UserFilters
 *
 * @package Src\Models
 */
class UserFilters {
    private ?int $page;
    private ?int $perPage;
    private ?string $search;
    private ?string $orderBy;
    private ?string $orderDir;

    /**
     * UserFilters constructor.
     */
    private function __construct(
        ?int $page = null,
        ?int $perPage = null,
        ?string $search = null,
        ?string $orderBy = null,
        ?string $orderDir = null
    ) {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->search = $search;
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
    }

    /**
     * Create a new instance of UserFilters
     *
     * @param array $filters
     * @return self
     */
    public static function createFromArray(array $filters): self {
        return new self(
            $filters['page'] ?? null,
            $filters['perPage'] ?? null,
            $filters['search'] ?? null,
            $filters['orderBy'] ?? null,
            $filters['orderDir'] ?? null
        );
    }

    /**
     * Get the page
     *
     * @return int|null
     */
    public function getPage(): ?int {
        return $this->page;
    }

    /**
     * Get the per page
     *
     * @return int|null
     */
    public function getPerPage(): ?int {
        return $this->perPage;
    }

    /**
     * Get the search
     *
     * @return string|null
     */
    public function getSearch(): ?string {
        return $this->search;
    }

    /**
     * Get the order by
     *
     * @return string|null
     */
    public function getOrderBy(): ?string {
        return $this->orderBy;
    }

    /**
     * Get the order direction
     *
     * @return string|null
     */
    public function getOrderDir(): ?string {
        return $this->orderDir;
    }
}