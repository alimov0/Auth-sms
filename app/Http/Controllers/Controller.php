<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    protected function success($data = [], string $message = 'Operation successful', int $status = 200)
    {
        return response()->json([
            'success' => true,
            'status'  => $status,
            'message' => $message,
            'data'    => $data ?? (object)[], // doim object qaytadi
            'errors'  => null,
        ], $status);
    }

    protected function responsePagination($paginator, $data = [], string $message = 'Operation successful', int $status = 200)
    {
        $pagination = null;

        if ($paginator instanceof LengthAwarePaginator) {
            $pagination = [
                'current_page' => $paginator->currentPage(),
                'total_pages'  => $paginator->lastPage(),
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'links'        => [
                    'first' => $paginator->url(1),
                    'last'  => $paginator->url($paginator->lastPage()),
                    'prev'  => $paginator->previousPageUrl(),
                    'next'  => $paginator->nextPageUrl(),
                ]
            ];
        }

        return response()->json([
            'success'    => true,
            'status'     => $status,
            'message'    => $message,
            'data'       => $data,
            'pagination' => $pagination,
            'errors'     => null,
        ], $status);
    }

    protected function error(string $message = 'An error occurred', int $status = 400, array $errors = [])
    {
        return response()->json([
            'success' => false,
            'status'  => $status,
            'message' => $message,
            'data'    => (object)[], // doim object qaytadi
            'errors'  => !empty($errors) ? $errors : null,
        ], $status);
    }
}
