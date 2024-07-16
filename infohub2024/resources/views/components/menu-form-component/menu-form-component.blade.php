@php
    $isEdit = isset($menuItem);
    $formAction = $isEdit ? route('menu.menu-items.update', $menuItem->id) : route('menu.menu-items.store');
    $formMethod = $isEdit ? 'PATCH' : 'POST';
@endphp

<div id="menu-form-component">
    <form id="create-menu-item-form" action="{{ $formAction }}" method="POST">
        @csrf
        @if($isEdit)
            @method($formMethod)
        @endif

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="type">Typ zakładki:</label>
                    <select id="type" name="type" required>
                        <option value="main" @if($isEdit && $menuItem->type == 'main') selected @endif>Główna</option>
                        <option value="sub" @if($isEdit && $menuItem->type == 'sub') selected @endif>Podrzędna</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="name">Nazwa zakładki:</label>
                    <input type="text" id="name" name="name" value="{{ $isEdit ? $menuItem->name : '' }}" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="slug">Tagi (oddzielone przecinkami):</label>
                    <input type="text" id="slug" name="slug" value="{{ $isEdit ? $menuItem->slug : '' }}" required>
                </div>

                <div class="form-group">
                    <label for="parent_id">Element nadrzędny:</label>
                    <select id="parent_id" name="parent_id">
                        <option value="">Brak (jest to element nadrzędny)</option>
                        @foreach($menuItemsToSelect as $item)
                            <option value="{{ $item->id }}" @if($isEdit && $menuItem->parent_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="start">Zakładka widoczna od:</label>
                    <input type="date" id="start" name="visibility_start" value="{{ $isEdit && $menuItem->start ? $menuItem->start->format('Y-m-d') : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="end">Zakładka widoczna do:</label>
                    <input type="date" id="end" name="visibility_end" value="{{ $isEdit && $menuItem->end ? $menuItem->end->format('Y-m-d') : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="menu_banner">Przypisanie banera:</label>
                    <select id="menu_banner" name="banner">
                        <option value="random_banner" @if($isEdit && $menuItem->banner == 'random_banner') selected @endif>Baner losowy</option>
                        <option value="dedicated_banner" @if($isEdit && $menuItem->banner == 'dedicated_banner') selected @endif>Baner dedykowany</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="1" @if($isEdit && $menuItem->status == 1) selected @endif>Aktywny</option>
                        <option value="0" @if($isEdit && $menuItem->status == 0) selected @endif>Nieaktywny</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="owners">Opiekuny/Administratorzy:</label>
            <div class="picklist-container">
                <div class="picklist">
                    <h5>Wszyscy użytkownicy</h5>
                    <ul id="all-users" class="picklist-list">
                        @foreach($nonOwners as $user)
                            {{--                                    <pre>{{ json_encode($user, JSON_PRETTY_PRINT) }}</pre>--}}
                            <li class="picklist-item" data-user-id="{{ $user->id }}">{{ $user->name }} {{ $user->surname }} ({{ $user->usersGroup->name ?? 'brank grupy' }} ) -- {{ $user->branch->name ?? 'brak koncesji'}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="picklist-buttons">
                    <button type="button" id="add-button" class="btn btn-secondary">&gt;</button>
                    <button type="button" id="remove-button" class="btn btn-secondary">&lt;</button>
                </div>
                <div class="picklist">
                    <h5>Opiekun(y)/Administrator(zy)</h5>
                    <ul id="selected-owners" class="picklist-list">
                        @foreach($users as $user)
                            @if(in_array($user->id, $currentOwners ?? []))
                                <li class="picklist-item" data-user-id="{{ $user->id }}">{{ $user->name }} {{ $user->surname }} ({{ $user->usersGroup->name ?? 'brank grupy' }} ) -- {{ $user->branch->name ?? 'brak koncesji'}}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <input type="hidden" name="owners" id="owners-input">
        </div>


        <div class="form-actions d-flex justify-content-between">
            <div>
                <div class="table-button">
                    <a href="#" id="submit-form-button" class="btn">{{ $isEdit ? 'Aktualizuj' : 'Dodaj' }}</a>
                </div>
            </div>
            <div class="d-flex">
                @if($isEdit)
                    <div class="table-button-2 ml-2">
                        <a href="#" id="delete-menu-item" data-menu-item-id="{{ $menuItem->id }}" class="btn">Usuń zakładkę</a>
                    </div>
                @endif
                <div class="table-button-2 ml-2">
                    <a href="{{ route('menu.structure') }}" class="btn">{{ __('Anuluj') }}</a>
                </div>
                <div class="table-button-2 ml-2">
                    <a href="#" onclick="event.preventDefault(); this.closest('form').reset();" class="btn">Wyczyść</a>
                </div>
            </div>
        </div>
    </form>
</div>

@include('components.menu-form-component.menu-delete-modal')
