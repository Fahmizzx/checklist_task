<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table            = 'tb_pages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['page_key', 'page_name', 'parent_key', 'url', 'icon', 'menu_order'];

    protected $validationRules = [
        'page_key'  => 'required|alpha_dash|is_unique[tb_pages.page_key,id,{id}]',
        'page_name' => 'required|string',
    ];

    protected $validationMessages = [
        'page_key' => [
            'required'   => 'Kunci Halaman tidak boleh kosong.',
            'alpha_dash' => 'Kunci Halaman hanya boleh berisi huruf, angka, underscore, atau strip.',
            'is_unique'  => 'Kunci Halaman ini sudah digunakan.',
        ],
        'page_name' => [
            'required' => 'Nama Halaman tidak boleh kosong.',
        ],
    ];
}
