@extends('layouts.app')

@section('content')
<div class="">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                {{-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Szczegóły zgłoszenia
                </h2> --}}

                <p class="content-tab-name">{{ __('Zgłoszenia / Szczegóły zgłoszenia') }}</p>

                <div class="mt-4">
                    <div><strong>Nazwa firmy:</strong> {{ $application->company_name }}</div>
                    <div><strong>Imię i nazwisko / Nazwa koncesji:</strong> {{ $application->name }} {{ $application->surname }}</div>
                    <div><strong>Email:</strong> {{ $application->email }}</div>
                    <div><strong>Telefon:</strong> {{ $application->phone }}</div>
                    <div><strong>Data zgłoszenia:</strong> {{ $application->created_at->format('Y-m-d H:i:s') }}</div>
                </div>

                <form action="{{ route('menu.users.applications.updateStatus', $application->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mt-4">
                        <label for="branch_id">Koncesja:</label>
                        <select name="branch_id" id="branch_id" class="form-control">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- User Group Dropdown --}}
                    <div class="mt-4">
                        <label for="users_groups_id">Grupa użytkowników:</label>
                        <select name="users_groups_id" id="users_groups_id" class="form-control">
                            @foreach($userGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="status" class="block">Status:</label>
                        <div>
                            <input type="radio" name="status" value="1" id="accept" {{ $application->status == 1 ? 'checked' : '' }} required>
                            <label for="accept">Akceptuj</label>
                        </div>
                        <div>
                            <input type="radio" name="status" value="2" id="reject" {{ $application->status == 2 ? 'checked' : '' }} required>
                            <label for="reject">Odrzuć</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="refused_comment" class="block">Powód odrzucenia:</label>
                        <textarea name="refused_comment" id="refused_comment" rows="3" class="w-full">{{ $application->refused_comment }}</textarea>
                    </div>

                    <div class="mt-4 flex justify-between">
                        <button type="submit" class="btn btn-save">Zapisz</button>
                        <p class="table-button">
                            <a href="{{ route('menu.users.applications.view') }}" class="btn ">Anuluj</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
