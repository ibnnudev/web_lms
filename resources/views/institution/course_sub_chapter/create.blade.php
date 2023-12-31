<x-app-layout>
    <x-breadcrumb name="course_sub_chapter.create" :data="$courseChapter" />

    <div class="md:grid 2xl:grid-cols-3 gap-4 2xl:gap-x-8">
        <x-card-container>
            <div class="text-gray-600">
                <p class="font-medium text-md">
                    Materi Praktik
                </p>
                <p class="text-xs 2xl:text-sm">
                    Lengkapi data materi teori dengan benar.
                </p>
                <div class="mt-8">
                    <x-input id="title" name="title" type="text" label="Judul Materi" />
                    {{-- only pdf --}}
                    <x-input id="file" name="file" type="file" label="File Materi Teori"
                        accept="application/pdf" />
                    <x-input id="video" name="video" type="file" label="File Materi Praktikum"
                        accept="video/*" />

                    <x-button title="Tambah Sub Materi" />
                </div>
            </div>
        </x-card-container>
        <div class="border-gray-200 border-2 border-dashed rounded-lg p-4">
            <h2 class="text-xs 2xl:text-sm font-medium mb-4">
                Preview Materi Teori
            </h2>
            <embed src="" class="w-full rounded-lg" style="height: 600px">
        </div>
        <div class="border-gray-200 border-2 border-dashed rounded-lg p-4">
            <h2 class="text-xs 2xl:text-sm font-medium mb-4">
                Preview Materi Praktikum
            </h2>
            <iframe src="" frameborder="0" style="height: 300px"></iframe>
        </div>
    </div>

    @push('js-internal')
        <script>
            $(function() {
                $('#file').change(function() {
                    const file = $(this)[0].files[0];
                    const fileReader = new FileReader();
                    // if not pdf
                    if (file.type != 'application/pdf') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'File harus berupa pdf!',
                        });
                        $(this).val('');
                        return;
                    }
                    fileReader.onloadend = function() {
                        $('embed').attr('src', fileReader.result + '#toolbar=0');
                    }
                    fileReader.readAsDataURL(file);
                });

                $('button[type="submit"]').click(function(e) {
                    e.preventDefault();
                    let title = $('#title').val();
                    let file = $('#file').val();
                    let video = $('#video').val();

                    if (title == '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Judul materi tidak boleh kosong!',
                        });
                        return;
                    }

                    if (file == '' && video == '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Mohon diisi salah satu file materi!',
                        });
                        return;
                    }

                    let formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('title', title);
                    formData.append('file', $('#file')[0].files[0]);
                    formData.append('video', $('#video')[0].files[0]);

                    let url = "{{ route('admin.course-sub-chapter.store', $courseChapter->id) }}";
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            '{{ route('admin.course-sub-chapter.index', $courseChapter->id) }}';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: data.message,
                                });
                            }
                        },
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
