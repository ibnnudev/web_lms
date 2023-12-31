<x-app-layout>
    <x-breadcrumb name="institution-course.index" />

    <x-card-container>
        <div class="text-end mb-4">
            <x-link-button type="button" title="Tambah Kelas" class="bg-gray-700 hover:bg-gray-600"
                route="{{ route('institution.course.create') }}" />
        </div>
        <table id="courseTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Status Permintaan</th>
                    <th>Status Unggah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    <!-- Image Preview Modal -->
    <div id="image_preview" class="modal">
        <img id="imagePreview" class="w-full" src="" alt="">
    </div>

    @push('js-internal')
        <script>
            function destroy(id, title) {
                console.log(id, title);
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: `Kelas ${title} akan dinonaktifkan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('institution.course.destroy', ':id') }}";
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: `Kelas ${title} berhasil dihapus!`,
                                        icon: 'success',
                                        showConfirmButton: false,
                                    }).then((result) => {
                                        $('#courseTable').DataTable().ajax.reload();
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: `Kelas ${title} gagal dihapus!`,
                                        icon: 'error',
                                    })
                                }
                            },
                        });
                    }
                })
            }

            function restore(id, title) {
                console.log(id, title);
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: `Kelas ${title} akan diaktifkan kembali!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('institution.course.restore', ':id') }}";
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: `Kelas ${title} berhasil diaktifkan kembali!`,
                                        icon: 'success',
                                        showConfirmButton: false,
                                    }).then((result) => {
                                        $('#courseTable').DataTable().ajax.reload();
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: `Kelas ${title} gagal diaktifkan kembali!`,
                                        icon: 'error',
                                    })
                                }
                            },
                        });
                    }
                })
            }

            function publish(id, title) {
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: `Kelas ${title} akan dipublikasikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, publikasikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('institution.course.publish', ':id') }}";
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: `Kelas ${title} berhasil dipublikasikan!`,
                                        icon: 'success',
                                        showConfirmButton: false,
                                    }).then((result) => {
                                        $('#courseTable').DataTable().ajax.reload();
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: `Kelas ${title} gagal dipublikasikan!`,
                                        icon: 'error',
                                    })
                                }
                            },
                        });
                    }
                })
            }

            function unpublish(id, title) {
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: `Kelas ${title} tidak akan dipublikasikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, unpublikasikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('institution.course.unpublish', ':id') }}";
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: `Kelas ${title} berhasil diunpublikasikan!`,
                                        icon: 'success',
                                        showConfirmButton: false,
                                    }).then((result) => {
                                        $('#courseTable').DataTable().ajax.reload();
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: `Kelas ${title} gagal diunpublikasikan!`,
                                        icon: 'error',
                                    })
                                }
                            },
                        });
                    }
                })
            }

            $(function() {
                $('#courseTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('institution.course.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'category',
                            name: 'category'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'main_image',
                            name: 'main_image'
                        },
                        {
                            data: 'price',
                            name: 'price'
                        },
                        {
                            data: 'request_status',
                            name: 'request_status'
                        },
                        {
                            data: 'upload_status',
                            name: 'upload_status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
