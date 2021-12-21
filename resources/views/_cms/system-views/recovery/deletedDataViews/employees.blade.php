<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Employee Number</th>
        <th>Name</th>
        <th>Designation</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($employeeData as $employee)
        <?php try { ?>
        <tr>
            <td>{{ $employee->id }}</td>
            <td>{{ $employee->employee_number }}</td>
            <td>{{ $employee->FirstName }} {{ $employee->LastName }}</td>
            <td>{{ $employee->Designation->DesignationName }}</td>
            <td>{{ $employee->deleted_at }}</td>
            <td>
                <form action="{{ route('recover.employees', $employee->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-dark" title="Restore"><i class="fas fa-plus-square"></i></button>
                </form>
            </td>
        </tr>
        <?php } catch (ErrorException $e) { ?>

            <?php } ?>
    @empty
    @endforelse
    </tbody>
</table>
