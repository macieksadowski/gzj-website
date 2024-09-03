<div class="modal fade" id="editContractsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Umowy - {{ $event->name }} - {{ date('Y', strtotime($event->date)) }}</h5>
            </div>
            <form method="POST">
                @csrf
                <input type="hidden" id="event" name="event" value="{{ $event->id }}">
                <div class="modal-body">
                    <table class="table" id="edit-contracts-form-table">
                        <thead>
                            <th>Kto</th>
                            <th>Kwota</th>
                            <th>Rodzaj</th>
                            <th></th>
                        </thead>
                        @foreach ($event->contracts as $contract)
                            <tr class="edit-contracts-form-entry">
                                <td>{{ $contract->member->display_name }}</td>
                                <td>{{ $contract->contract_amount }} zł</td>
                                <td>{{ $contract->type->value }}</td>
                                <td class="btn btn-danger deleteBtn deleteDb">
                                    <label for="deleteCheckbox{{ $contract->id }}">
                                        <i class="bi bi-trash"></i>
                                    </label>
                                    <input id="deleteCheckbox{{ $contract->id }}" type="checkbox"
                                        name="deletedContracts[]" value="{{ $contract->id }}">
                                </td>
                            </tr>
                        @endforeach
                        <tr id="edit-contracts-form-add-tr">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="btn btn-success addNewBtn"><i class="bi bi-plus-circle"></i></td>
                        </tr>
                        <tr id="edit-contracts-form-new-entry">
                            <td>
                                <select id="contract-person" name="[contract-person]">
                                    @foreach ($members as $member)
                                        <option value="{{ $member->id }}"
                                            @if ($loop->first) selected @endif>{{ $member->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="currency" id="contract-amount" name="[contract-amount]"
                                    value="0,00 zł" />
                            </td>
                            <td>
                                <select id="contract-type" name="[contract-type]">
                                    @foreach ($contractTypes as $type)
                                        <option value="{{ $type->id }}"
                                            @if ($loop->first) selected @endif>{{ $type->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="btn btn-danger deleteBtn deleteTmp">
                                <i class="bi bi-trash"></i>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="editContracts" class="btn btn-primary">Zapisz</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="cancelButton">Anuluj</button>
                </div>
            </form>
        </div>
    </div>
</div>
