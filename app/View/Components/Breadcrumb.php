<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;
use Log1x\Crumb\Facades\Crumb;
use WP_Term;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * The breadcrumb items.
     *
     * @return array<int, array{id: mixed, label: string, url: string|null}>
     */
    public function items()
    {
        if (is_singular('post')) {
            return $this->postItems();
        }

        return Crumb::build()->toArray();
    }

    /**
     * Insights / report posts: Home → Insights → primary category.
     *
     * @return array<int, array{id: mixed, label: string, url: string|null}>
     */
    protected function postItems(): array
    {
        $items = [
            [
                'id' => null,
                'label' => get_bloginfo('name', 'display'),
                'url' => home_url('/'),
            ],
            [
                'id' => null,
                'label' => __('Insights', 'sage'),
                'url' => home_url('/insights/'),
            ],
        ];

        $primary = $this->primaryCategory();

        if ($primary instanceof WP_Term) {
            $items[] = [
                'id' => $primary->term_id,
                'label' => $primary->name,
                'url' => home_url('/insights/?_insights_topics=' . $primary->slug),
            ];
        }

        return $items;
    }

    protected function primaryCategory(): ?WP_Term
    {
        $post_id = get_the_ID();

        if (! $post_id) {
            return null;
        }

        if (function_exists('yoast_get_primary_term_id')) {
            $primary_id = yoast_get_primary_term_id('category', $post_id);

            if ($primary_id) {
                $term = get_term((int) $primary_id, 'category');

                if ($term instanceof WP_Term) {
                    return $term;
                }
            }
        }

        $categories = get_the_category($post_id);

        return (! empty($categories) && $categories[0] instanceof WP_Term)
            ? $categories[0]
            : null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return $this->view('components.breadcrumb');
    }
}
