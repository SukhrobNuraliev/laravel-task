<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(auth()->user()->role->name == 'manager')
                        <span class="text-blue-500 font-bold text-xl">Received Applications</span>

                        <div class='mt-5'>

                            @foreach($applications as $application)
                                <div class="rounded-xl border p-5 mt-5 shadow-md w-9/12 bg-white">
                                    <div class="flex w-full items-center justify-between border-b pb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-8 w-8 rounded-full bg-slate-400 bg-[url('https://i.pravatar.cc/32')]"></div>
                                            <div class="text-lg font-bold text-slate-700">{{ $application->user->name }}</div>
                                        </div>
                                        <div class="flex items-center space-x-8">
                                            <button class="rounded-2xl border bg-neutral-100 px-3 py-1 text-xs font-semibold">
                                                # {{ $application->id }}
                                            </button>
                                            <div class="text-xs text-neutral-500">{{ $application->created_at }}</div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="mt-4 mb-3">
                                                <div class="mb-3 text-xl font-bold">{{ $application->subject }}</div>
                                                <div class="text-sm text-neutral-600">{{ $application->message }}</div>
                                            </div>
                                            <div>
                                                <div class="flex items-center justify-between text-slate-500">
                                                    {{ $application->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="border m-6 p-6 rounded hover:bg-gray-50 transition cursor-pointer flex flex-col items-center">
                                                @if(is_null($application->file_url))
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    No File
                                                @else
                                                    <a href="{{ asset('storage/'.$application->file_url) }}" target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($application->answer()->exists())
                                    <div>
                                        <hr class="border">
                                        <h3 class="text-xs font-bold mt-2 text-indigo-600">Answer:</h3>
                                        <p>{{ $application->answer->body }}</p>
                                    </div>
                                    @else
                                        <div class="flex justify-end">
                                            <a href="{{ route('answers.create', ['application' => $application->id]) }}" type="button" class="bg-green-500 text-white px-3 py-1 rounded font-medium text-sm mx-3 hover:bg-green-600 transition duration-200 each-in-out">
                                                Answer
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            {{ $applications->links() }}

                        </div>

                    @elseif(auth()->user()->role->name == 'client')

                        @if(session()->has('error'))
                            <div class="flex bg-blue-100 rounded-lg p-4 mb-4 text-sm text-blue-700" role="alert">
                                <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <span class="font-medium">! </span> {{ session()->get('error') }}
                                </div>
                            </div>
                        @endif

                        <div class='flex items-center '>
                            <div class='w-full max-w-lg px-10 py-8 mx-auto bg-white rounded-lg shadow-xl'>
                                <div class='max-w-md mx-auto space-y-6'>
                                    <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <h2 class="text-2xl font-bold ">Submit your application</h2>
                                        <hr class="my-6">
                                        <label class="uppercase text-sm font-bold opacity-70">Subject</label>
                                        <input type="text" name="subject" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded border-2 border-slate-200 focus:border-slate-600 focus:outline-none">
                                        <label class="uppercase text-sm font-bold opacity-70">Message</label>
                                        <textarea rows="5" name="message" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded border-2 border-slate-200 focus:border-slate-600 focus:outline-none"></textarea>
                                        <label class="uppercase text-sm font-bold opacity-70">File</label>
                                        <input type="file" name="file" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded border-2 border-slate-200 focus:border-slate-600 focus:outline-none">
                                        <input type="submit" class="py-3 px-6 my-2 bg-emerald-500 text-white font-medium rounded hover:bg-indigo-500 cursor-pointer ease-in-out duration-300" value="Send">
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
