<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Articles",
 *     description="Endpoint untuk mengelola data artikel"
 * )
 */

 /**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     title="Article",
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Judul artikel"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Deskripsi artikel"
 *     )
 * )
 */

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Dapatkan daftar artikel",
     *     tags={"Articles"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Daftar artikel",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         )
     * )
     * )
     */
    public function index()
    {
        // Dapatkan daftar artikel dan tampilkan
        $articles = Article::all();
        return response()->json(['success' => true, 'data' => $articles, 'message' => 'Data artikel berhasil diambil'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Buat artikel baru",
     *     tags={"Articles"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Artikel berhasil dibuat",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         )
     * )
     * )
     */
    public function store(Request $request)
    {
        // Validasi dan simpan artikel baru
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $article = Article::create($validatedData);

        return response()->json(['success' => true, 'data' => $article, 'message' => 'Artikel berhasil dibuat'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     summary="Dapatkan detail artikel",
     *     tags={"Articles"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID artikel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detail artikel",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         )
     * )
     * )
     */
    public function show($id)
    {
        // Dapatkan dan tampilkan detail artikel
        $article = Article::findOrFail($id);
        if (!$article) {
            return response()->json(['success' => false, 'message' => 'Artikel tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $article, 'message' => 'Detail artikel'], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/articles/{id}",
     *     summary="Perbarui artikel",
     *     tags={"Articles"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID artikel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Artikel berhasil diperbarui",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         )
     * )
     * )
     */
    public function update(Request $request, $id)
    {
        // Validasi dan perbarui artikel
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $article = Article::findOrFail($id);
        $article->update($validatedData);

        return response()->json(['success' => true, 'data' => $article, 'message' => 'Artikel berhasil diperbarui'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/articles/{id}",
     *     summary="Hapus artikel",
     *     tags={"Articles"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID artikel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Artikel berhasil dihapus",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         )
     * )
     * )
     */
    public function destroy($id)
    {
        // Hapus artikel
        $article = Article::findOrFail($id);
        if (!$article) {
            return response()->json(['success' => false, 'message' => 'Artikel tidak ditemukan'], 404);
        }
        $article->delete();

        return response(null, 204);
    }
}
