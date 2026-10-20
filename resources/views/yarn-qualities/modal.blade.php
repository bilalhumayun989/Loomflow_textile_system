<div id="modalYarnQualities" class="fixed inset-0 z-[60] hidden items-center justify-center bg-zinc-950/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-3xl border border-zinc-200 bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between border-b border-zinc-100 pb-4">
            <div>
                <h2 class="text-lg font-extrabold text-zinc-900">Yarn Quality List</h2>
                <p class="mt-1 text-xs text-zinc-500">Add a quality once, then select it in bag and sizing forms.</p>
            </div>
            <button type="button" onclick="closeModal('modalYarnQualities')" class="rounded-lg p-1 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900" aria-label="Close yarn quality list">✕</button>
        </div>

        <div class="mt-4 max-h-40 overflow-y-auto rounded-xl border border-zinc-100 bg-zinc-50 p-3">
            @forelse($yarnQualities as $yarnQuality)
                <span class="mb-1 mr-1 inline-flex rounded-lg border border-zinc-200 bg-white px-2.5 py-1 text-xs font-semibold text-zinc-700">{{ $yarnQuality->name }}</span>
            @empty
                <span class="text-xs text-zinc-400">No yarn qualities yet.</span>
            @endforelse
        </div>

        <form action="{{ route('yarn-qualities.store') }}" method="POST" class="mt-4 space-y-3">
            @csrf
            <div>
                <label for="newYarnQualityName" class="mb-1 block text-xs font-semibold text-zinc-600">New Yarn Quality *</label>
                <input id="newYarnQualityName" type="text" name="name" required maxlength="100" value="{{ old('name') }}" placeholder="e.g. 30/1 PC Combed" class="w-full rounded-xl border border-zinc-300 bg-zinc-50 px-3 py-2.5 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                @error('name')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
            </div>
            <div class="flex justify-end gap-2 border-t border-zinc-100 pt-3">
                <button type="button" onclick="closeModal('modalYarnQualities')" class="rounded-xl bg-zinc-100 px-4 py-2 text-xs font-semibold text-zinc-600">Close</button>
                <button type="submit" class="rounded-xl bg-zinc-900 px-5 py-2 text-xs font-bold text-white hover:bg-zinc-700">Add Yarn Quality</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const createdYarnQuality = @js(session('createdYarnQuality'));
    if (createdYarnQuality) {
        document.querySelectorAll('[data-yarn-quality-select]').forEach((select) => {
            select.value = createdYarnQuality;
        });
    }
    @if($errors->has('name'))
        openModal('modalYarnQualities');
    @endif
</script>
@endpush
