<?php

use Illuminate\Http\Request;

if (!function_exists('JsonOrViewChecker')) {
    function JsonOrViewChecker(Request $request, string $routeName, array $data = [], int $status = 200)
    {
        $message = $data['message'] ?? 'Operación exitosa';

        if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
            return response()->json(['message' => $message] + $data, $status);
        }

        return redirect()->route($routeName)->with('success', $message);
    }
}
