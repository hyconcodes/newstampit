<?php

use Livewire\Volt\Component;
use App\Models\School;

new class extends Component {
    public $school, $students, $igrsAdmins, $schoolFeesAdmins;
    public $totalStudents, $totalIgrsAdmins, $totalSchoolFeesAdmins;

    public function mount($id)
    {
        $this->school = School::find($id);
        if (!$this->school) {
            abort(404);
        }
        $this->students = $this->school->students()->latest()->take(8)->get() ?? null;
        $this->igrsAdmins = $this->school->igrsAdmins()->latest()->take(8)->get() ?? null;
        $this->schoolFeesAdmins = $this->school->schoolFeesAdmins()->latest()->take(8)->get() ?? null;

        $this->totalStudents = $this->school->students()->count();
        $this->totalIgrsAdmins = $this->school->igrsAdmins()->count();
        $this->totalSchoolFeesAdmins = $this->school->schoolFeesAdmins()->count();
    }

    public function title(): string
    {
        return $this->school ? $this->school->name : 'School Details';
    }
}; ?>

<div>
    <x-slot name="title">{{ $school->name . ' Details' }}</x-slot>

    <div class="bg-zinc-50 dark:bg-zinc-900 min-h-screen">
        <div class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">
                            {{ $school->name }} Dashboard
                        </h1>
                        <p class="text-zinc-600 dark:text-zinc-300">
                            Comprehensive analytics and management for {{ $school->code ?? $school->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6 rounded-md">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-zinc-800 dark:text-white">Total Students</h2>
                    <p class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalStudents }}</p>
                </div>
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-zinc-800 dark:text-white">IGRS Admins</h2>
                    <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalIgrsAdmins }}</p>
                </div>
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-zinc-800 dark:text-white">School Fees Admins</h2>
                    <p class="mt-2 text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $totalSchoolFeesAdmins }}
                    </p>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-zinc-800 dark:text-white mb-4">Recent Students</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Matric No</th>
                                {{-- <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Course</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Level</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Status</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($students as $student)
                                <tr onclick="window.location.href = '{{ route('student.show', $student->id) }}'" class="cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                        {{ $student->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                                        {{ $student->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                                        {{ $student->matric_no ?? 'N/A' }}</td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">{{ $student->level }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->status === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                    {{ $student->status }}
                                                </span>
                                            </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Admins Section (Optional Add Later) -->

            <!-- IGRS Admins Table -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-zinc-800 dark:text-white mb-4">Recent IGRS Admins</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Email</th>
                                {{-- <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Department</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($igrsAdmins as $admin)
                                <tr onclick="window.location.href = '{{ route('staffs', $admin->id) }}'"
                                    class="cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                        {{ $admin->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                                        {{ $admin->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                                        {{ $admin->department }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- School Fees Admins Table -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-zinc-800 dark:text-white mb-4">Recent School Fees Admins</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Email</th>
                                {{-- <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Department</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($schoolFeesAdmins as $admin)
                                <tr onclick="window.location.href = '{{ route('staffs', $admin->id) }}'"
                                    class="cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                        {{ $admin->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                                        {{ $admin->email }}</td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                                        {{ $admin->department }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
