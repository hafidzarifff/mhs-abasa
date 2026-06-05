<?php

use App\Models\Question;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;

layout('layouts.app');
title('Data Pertanyaan');

new class extends Component {
    use WithPagination;

    public $kode_pertanyaan = '';
    public $pertanyaan = '';
    public $template_pertanyaan = '';
    public $question_id = null;
    public $isOpen = false;

    public function create()
    {
        $this->resetFields();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'kode_pertanyaan' => 'required|unique:questions,kode_pertanyaan',
            'pertanyaan' => 'required|string',
            'template_pertanyaan' => 'required|string',
        ]);

        Question::create([
            'kode_pertanyaan' => $this->kode_pertanyaan,
            'pertanyaan' => $this->pertanyaan,
            'template_pertanyaan' => $this->template_pertanyaan,
        ]);

        $this->closeModal();
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $this->question_id = $id;
        $this->kode_pertanyaan = $question->kode_pertanyaan;
        $this->pertanyaan = $question->pertanyaan;
        $this->template_pertanyaan = $question->template_pertanyaan;
        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'kode_pertanyaan' => ['required', Rule::unique('questions')->ignore($this->question_id)],
            'pertanyaan' => 'required|string',
            'template_pertanyaan' => 'required|string',
        ]);

        $question = Question::findOrFail($this->question_id);
        $question->update([
            'kode_pertanyaan' => $this->kode_pertanyaan,
            'pertanyaan' => $this->pertanyaan,
            'template_pertanyaan' => $this->template_pertanyaan,
        ]);

        $this->closeModal();
    }

    public function delete($id)
    {
        Question::findOrFail($id)->delete();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetFields();
        $this->resetValidation();
    }

    private function resetFields()
    {
        $this->kode_pertanyaan = '';
        $this->pertanyaan = '';
        $this->template_pertanyaan = '';
        $this->question_id = null;
    }

    public function with(): array
    {
        $sorted = Question::all()
            ->sortBy(function ($q) {
                return (int) filter_var($q->kode_pertanyaan, FILTER_SANITIZE_NUMBER_INT);
            })
            ->values();

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $items = $sorted->slice(($page - 1) * $perPage, $perPage);

        return [
            'questions' => new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $sorted->count(),
                $perPage,
                $page,
                ['path' => request()->url()]
            )
        ];
    }
}; ?>

<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Data Pertanyaan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar pertanyaan skrining kesehatan mental.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 bg-purple-700 hover:bg-purple-800 transition-colors border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Pertanyaan
            </button>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider w-24">Kode</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Pertanyaan</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Template Pertanyaan</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider w-32 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($questions as $question)
                        <tr class="hover:bg-slate-50 transition-colors" wire:key="question-{{ $question->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                {{ $question->kode_pertanyaan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $question->pertanyaan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $question->template_pertanyaan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                <button wire:click="edit({{ $question->id }})" class="text-blue-600 hover:text-blue-900 transition-colors mr-3 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </button>
                                <button wire:click="delete({{ $question->id }})" wire:confirm="Yakin ingin menghapus?" class="text-red-600 hover:text-red-900 transition-colors inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($questions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                {{ $questions->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    {{-- Modal CRUD --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Card --}}
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">
                                {{ $question_id ? 'Edit Pertanyaan' : 'Tambah Pertanyaan' }}
                            </h3>
                            <div class="mt-4">
                                {{-- Form Fields --}}
                                <div class="mb-4">
                                    <label for="kode_pertanyaan" class="block text-sm font-medium text-gray-700">Kode</label>
                                    <input type="text" id="kode_pertanyaan" wire:model="kode_pertanyaan" placeholder="Contoh: Q1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                                    @error('kode_pertanyaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="pertanyaan" class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                                    <textarea id="pertanyaan" wire:model="pertanyaan" rows="3" placeholder="Masukkan teks pertanyaan..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"></textarea>
                                    @error('pertanyaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="template_pertanyaan" class="block text-sm font-medium text-gray-700">Template Pertanyaan</label>
                                    <textarea id="template_pertanyaan" wire:model="template_pertanyaan" rows="3" placeholder="Apakah :nama sering mengalami sakit kepala?" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Gunakan :nama sebagai placeholder untuk nama respondent. Contoh: "Apakah :nama sering mengalami sakit kepala?"</p>
                                    @error('template_pertanyaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Footer Buttons --}}
                    <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="{{ $question_id ? 'update' : 'store' }}" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-purple-700 border border-transparent rounded-md shadow-sm hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" wire:click="closeModal" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
