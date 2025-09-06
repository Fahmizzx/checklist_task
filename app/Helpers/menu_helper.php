<?php

use App\Modules\Admin\Models\PageModel;

if (!function_exists('generateMenu')) {
    /**
     * Menghasilkan HTML untuk menu sidebar secara dinamis.
     * Versi ini tidak memeriksa izin, hanya membangun struktur menu dari tb_pages.
     */
    function generateMenu(): string
    {
        $pageModel = new PageModel();
        $pages = $pageModel->whereNotIn('id', [32, 33, 35])->orderBy('menu_order', 'ASC')->findAll();

        // Bangun struktur menu hierarkis (tree) dari data flat
        $tree = [];
        $references = [];
        foreach ($pages as $key => &$page) {
            $references[$page['page_key']] = &$page;
        }

        foreach ($pages as $key => &$page) {
            if ($page['parent_key'] && isset($references[$page['parent_key']])) {
                $references[$page['parent_key']]['children'][] = &$page;
            } else {
                $tree[] = &$page;
            }
        }

        // Mulai render dari item root
        return buildMenuItems($tree);
    }
}

if (!function_exists('buildMenuItems')) {
    /**
     * Fungsi rekursif untuk merender item menu dari struktur tree.
     */
    function buildMenuItems(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            // Cek apakah item ini adalah header (seperti FORM atau AKTIVITAS)
            if (empty($item['url']) && empty($item['parent_key'])) {
                $html .= '<div class="menu-item"><div class="menu-content pt-8 pb-2"><span class="menu-section text-muted text-uppercase fs-8 ls-1">' . esc($item['page_name']) . '</span></div></div>';
                if (!empty($item['children'])) {
                    $html .= buildMenuItems($item['children']);
                }
                continue;
            }

            // Cek apakah item ini adalah dropdown (memiliki anak)
            if (!empty($item['children'])) {
                $html .= '<div data-kt-menu-trigger="click" class="menu-item menu-accordion">';
                $html .= '<span class="menu-link">';
                if ($item['icon']) {
                    $html .= '<span class="menu-icon"><i class="' . esc($item['icon']) . ' fs-2"></i></span>';
                }
                $html .= '<span class="menu-title">' . esc($item['page_name']) . '</span>';
                $html .= '<span class="menu-arrow"></span>';
                $html .= '</span>';
                $html .= '<div class="menu-sub menu-sub-accordion menu-active-bg">';
                $html .= buildMenuItems($item['children']); // Panggil rekursif
                $html .= '</div>';
                $html .= '</div>';
            } 
            // Cek apakah item ini adalah link tunggal
            elseif (!empty($item['url'])) {
                $html .= '<div class="menu-item">';
                $html .= '<a class="menu-link" href="' . base_url(esc($item['url'])) . '">';
                if ($item['icon']) {
                    $html .= '<span class="menu-icon"><i class="' . esc($item['icon']) . ' fs-2"></i></span>';
                } else {
                     $html .= '<span class="menu-bullet"><span class="bullet bullet-dot"></span></span>';
                }
                $html .= '<span class="menu-title">' . esc($item['page_name']) . '</span>';
                $html .= '</a>';
                $html .= '</div>';
            }
        }
        return $html;
    }
}
