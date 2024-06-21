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

                <div class="form-group">
                    <label for="owners">Opiekuny/Administratorzy:</label>
                    <div class="picklist-container">
                        <div class="picklist">
                            <h5>Wszyscy użytkownicy</h5>
                            <ul id="all-users" class="picklist-list">
                                @foreach($users as $user)
                                    @if(!$isEdit || !in_array($user->id, $menuItem->owners->pluck('id')->toArray()))
                                        <li class="picklist-item" data-user-id="{{ $user->id }}">{{ $user->name }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="picklist-buttons">
                            <button type="button" id="add-button" class="btn btn-secondary">&gt;</button>
                            <button type="button" id="remove-button" class="btn btn-secondary">&lt;</button>
                        </div>
                        <div class="picklist">
                            <h5>Opiekuny/Administratorzy</h5>
                            <ul id="selected-owners" class="picklist-list">
                                @if($isEdit)
                                    @foreach($menuItem->owners as $user)
                                        <li class="picklist-item" data-user-id="{{ $user->id }}">{{ $user->name }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="owners[]" id="owners-input">
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="start">Zakładka widoczna od:</label>
                    <input type="date" id="start" name="start" value="{{ $isEdit && $menuItem->start ? $menuItem->start->format('Y-m-d') : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="end">Zakładka widoczna do:</label>
                    <input type="date" id="end" name="end" value="{{ $isEdit && $menuItem->end ? $menuItem->end->format('Y-m-d') : '' }}">
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
            </div>
        </div>
        <div class="form-actions d-flex justify-content-end">
            <div class="mr-auto">
                <div class="table-button">
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="btn">{{ $isEdit ? 'Aktualizuj' : 'Dodaj' }}</a>
                </div>
                @if($isEdit)
                    <div class="table-button-2 ml-2">
                        <a href="#" id="delete-menu-item" data-menu-item-id="{{ $menuItem->id }}" class="btn ">Usuń zakładkę</a>
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

<style>
    .table-button a, .table-button-2 a {
        text-transform: uppercase;
        font-size: 16px;
        color: #9D8C83;
        height: 26px;
        line-height: 26px;
        padding: 0 16px;
        border: 1px solid #594A41;
        font-family: 'CitroenLight', 'Arial', sans-serif;
        font-weight: normal;
        display: inline-block;
        text-align: center;
        background: none;
        cursor: pointer;
        text-decoration: none;
    }

    .table-button a:hover, .table-button-2 a:hover {
        background-color: #594A41;
        color: white;
    }

    .picklist-container {
        display: flex;
        align-items: center;
    }

    .picklist {
        width: 45%;
        margin: 0 10px;
    }

    .picklist-list {
        border: 1px solid #ccc;
        min-height: 200px;
        list-style: none;
        padding: 10px;
    }

    .picklist-item {
        padding: 5px;
        cursor: pointer;
        background-color: #f9f9f9;
        margin-bottom: 5px;
    }

    .picklist-item:hover {
        background-color: #e9e9e9;
    }

    .picklist-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .picklist-buttons .btn {
        margin: 5px 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allUsersList = document.getElementById('all-users');
        const selectedOwnersList = document.getElementById('selected-owners');
        const ownersInput = document.getElementById('owners-input');

        function updateOwnersInput() {
            const ownerIds = Array.from(selectedOwnersList.querySelectorAll('.picklist-item'))
                .map(item => item.getAttribute('data-user-id'));
            ownersInput.value = ownerIds.join(',');
        }

        document.getElementById('add-button').addEventListener('click', function () {
            Array.from(allUsersList.querySelectorAll('.picklist-item.selected')).forEach(item => {
                selectedOwnersList.appendChild(item);
                item.classList.remove('selected');
            });
            updateOwnersInput();
        });

        document.getElementById('remove-button').addEventListener('click', function () {
            Array.from(selectedOwnersList.querySelectorAll('.picklist-item.selected')).forEach(item => {
                allUsersList.appendChild(item);
                item.classList.remove('selected');
            });
            updateOwnersInput();
        });

        allUsersList.addEventListener('click', function (event) {
            if (event.target.classList.contains('picklist-item')) {
                event.target.classList.toggle('selected');
            }
        });

        selectedOwnersList.addEventListener('click', function (event) {
            if (event.target.classList.contains('picklist-item')) {
                event.target.classList.toggle('selected');
            }
        });

        updateOwnersInput();
    });
</script>
