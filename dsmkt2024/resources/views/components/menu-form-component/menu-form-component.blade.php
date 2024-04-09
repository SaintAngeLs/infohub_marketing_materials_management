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
                    <select id="owners" name="owners[]" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @if($isEdit && in_array($user->id, $menuItem->owners->pluck('id')->toArray())) selected @endif>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
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
                <button type="submit" class="btn btn-primary ">{{ $isEdit ? 'Aktualizuj' : 'Dodaj' }}</button>
                @if($isEdit)
                    <button type="button" class="btn btn-danger" id="delete-menu-item" data-menu-item-id="{{ $menuItem->id }}">Usuń zakładkę</button>
                @endif
                <button type="reset" class="btn btn-secondary ml-2">Wyczyść</button>
            </div>

        </div>

    </form>
</div>

@include('components.menu-form-component.menu-delete-modal')


