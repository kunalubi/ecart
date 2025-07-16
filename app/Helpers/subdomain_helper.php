<?php

if (!function_exists('getSubdomainStoreId')) {
    function getSubdomainStoreId()
    {
        $host = $_SERVER['HTTP_HOST'];
        $parts = explode('.', $host);
        $subdomain = $parts[0];
        $storeModel = new \App\Models\StoreModel();
        $store = $storeModel->where('subdomain', $subdomain)->first();
        return $store ? $store['id'] : null;
    }
}
