<div class="flex items-center gap-x-2">
    <x-edit-button title="Edit" route="{{ route('admin.quiz.edit', [$data->course_chapter_id, $data->id]) }}" />
    @if ($data->is_active)
        <x-delete-button onclick="destroy('{{ $data->id }}', '{{ $data->title }}')" />
        <x-link-button color="dark" title="{{ $data->questions->count() ? 'Lihat Soal' : 'Tambah Soal' }}"
            route="{{ route('admin.question.index', $data->id) }}" />
    @else
        <x-restore-button onclick="restore('{{ $data->id }}', '{{ $data->title }}')" />
    @endif
</div>
