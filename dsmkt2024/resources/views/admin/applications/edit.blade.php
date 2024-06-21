@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">
                    <a href="{{ route('menu.users.applications.view') }}" class="text-blue-600 hover:text-blue-900">{{ __('Zgłoszenia') }}</a> / {{ __('Szczegóły zgłoszenia') }}
                </p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><strong>Nazwa firmy:</strong> {{ $application->company_name }}</div>
                    <div><strong>Imię i nazwisko / Nazwa koncesji:</strong> {{ $application->name }} {{ $application->surname }}</div>
                    <div><strong>Email:</strong> {{ $application->email }}</div>
                    <div><strong>Telefon:</strong> {{ $application->phone }}</div>
                    <div><strong>Data zgłoszenia:</strong> {{ $application->created_at->format('Y-m-d H:i:s') }}</div>
                </div>

                <form action="{{ route('menu.users.applications.updateStatus', $application->id) }}" method="POST" class="mt-6">
                    @csrf
                    @method('PATCH')

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="branch_id" class="block font-medium text-sm text-gray-700">Koncesja:</label>
                            <select name="branch_id" id="branch_id" class="form-control mt-1 block w-full">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="users_groups_id" class="block font-medium text-sm text-gray-700">Grupa użytkowników:</label>
                            <select name="users_groups_id" id="users_groups_id" class="form-control mt-1 block w-full">
                                @foreach($userGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="status" class="block font-medium text-sm text-gray-700">Status:</label>
                        <div class="mt-2 space-y-2">
                            <div>
                                <input type="radio" name="status" value="1" id="accept" {{ $application->status == 1 ? 'checked' : '' }} required>
                                <label for="accept" class="ml-2">Akceptuj</label>
                            </div>
                            <div>
                                <input type="radio" name="status" value="2" id="reject" {{ $application->status == 2 ? 'checked' : '' }} required>
                                <label for="reject" class="ml-2">Odrzuć</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="refused_comment" class="block font-medium text-sm text-gray-700">Powód odrzucenia:</label>
                        <textarea name="refused_comment" id="refused_comment" rows="3" class="form-control mt-1 block w-full">{{ $application->refused_comment }}</textarea>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <div class="table-button">
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="btn">Zapisz</a>
                        </div>
                        <div class="table-button-2">
                            <a href="{{ route('menu.users.applications.view') }}" class="btn">Anuluj</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
