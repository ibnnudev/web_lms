<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\CourseCategoryInterface;
use App\Interfaces\CourseInterface;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $courseCategory;
    private $course;

    public function __construct(CourseCategoryInterface $courseCategory, CourseInterface $course)
    {
        $this->courseCategory = $courseCategory;
        $this->course = $course;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->course->getByUserId(auth()->user()->id))
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('main_image', function ($data) {
                    return view('admin.course.column.main_image', compact('data'));
                })
                ->addColumn('price', function ($data) {
                    return 'Rp. ' . number_format($data->price, 0, ',', '.');
                })
                ->addColumn('request_status', function ($data) {
                    return view('admin.course.column.request_status', [
                        'data' => $data
                    ]);
                })
                ->addColumn('upload_status', function ($data) {
                    return view('admin.course.column.upload_status', [
                        'data' => $data
                    ]);
                })
                ->addColumn('action', function ($data) {
                    return view('admin.course.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.course.index');
    }

    public function create()
    {
        return view('admin.course.create', [
            'courseCategories' => $this->courseCategory->getAll(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'category_id' => ['required'],
            'short_description' => ['required'],
            'description' => ['required'],
            'price' => ['required'],
            'main_image' => ['required'],
            'sneek_peek_1' => ['required'],
            'sneek_peek_2' => ['required'],
            'sneek_peek_3' => ['required'],
            'sneek_peek_4' => ['required'],
        ], [
            'title.required' => 'Judul harus diisi',
            'category_id.required' => 'Kategori harus diisi',
            'short_description.required' => 'Deskripsi singkat harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'price.required' => 'Harga harus diisi',
            'main_image.required' => 'Gambar utama harus diisi',
            'sneeek_peek_1.required' => 'Gambar sneek peek 1 harus diisi',
            'sneeek_peek_2.required' => 'Gambar sneek peek 2 harus diisi',
            'sneeek_peek_3.required' => 'Gambar sneek peek 3 harus diisi',
            'sneeek_peek_4.required' => 'Gambar sneek peek 4 harus diisi',
        ]);

        try {
            $this->course->store($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil ditambahkan',
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        return view('admin.course.edit', [
            'courseCategories' => $this->courseCategory->getAll(),
            'course' => $this->course->getById($id),
            'courseDiscount' => $this->course->discount($id)
        ]);
    }

    public function update($id)
    {
        $request = request();
        $request->validate([
            'title' => ['required'],
            'category_id' => ['required'],
            'short_description' => ['required'],
            'description' => ['required'],
            'price' => ['required'],
            'main_image' => ['nullable'],
            'sneek_peek_1' => ['nullable'],
            'sneek_peek_2' => ['nullable'],
            'sneek_peek_3' => ['nullable'],
            'sneek_peek_4' => ['nullable'],
        ], [
            'title.required' => 'Judul harus diisi',
            'category_id.required' => 'Kategori harus diisi',
            'short_description.required' => 'Deskripsi singkat harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'price.required' => 'Harga harus diisi',
            'main_image.required' => 'Gambar utama harus diisi',
            'sneeek_peek_1.required' => 'Gambar sneek peek 1 harus diisi',
            'sneeek_peek_2.required' => 'Gambar sneek peek 2 harus diisi',
            'sneeek_peek_3.required' => 'Gambar sneek peek 3 harus diisi',
            'sneeek_peek_4.required' => 'Gambar sneek peek 4 harus diisi',
        ]);

        try {
            $this->course->update($request->all(), $id);
            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil diubah',
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        $this->course->destroy($id);
        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }

    public function restore($id)
    {
        $this->course->restore($id);
        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dipulihkan',
        ]);
    }

    public function publish($id)
    {
        $this->course->publish($id);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil mempublish course'
        ]);
    }

    public function unpublish($id)
    {
        $this->course->unpublish($id);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil membatalkan publish course'
        ]);
    }
}