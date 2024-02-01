@extends('layouts.admin.index' )
@section('title', 'Properti Villa')

@section('content')
<div class="card">
    <div class="table-responsive text-nowrap">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Project</th>
					<th>Client</th>
					<th>Users</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody class="table-border-bottom-0">
				<tr>
					<td><i class="bx bxl-angular bx-sm text-danger me-3"></i> <span class="fw-medium">Angular Project</span></td>
					<td>Albert Cook</td>
					<td>
						<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
							<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" aria-label="Lilian Fuller" data-bs-original-title="Lilian Fuller">
							<img src="http://template.test/assets/img/avatars/5.png" alt="Avatar" class="rounded-circle">
							</li>
							<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" aria-label="Sophia Wilkerson" data-bs-original-title="Sophia Wilkerson">
							<img src="http://template.test/assets/img/avatars/6.png" alt="Avatar" class="rounded-circle">
							</li>
							<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" aria-label="Christina Parker" data-bs-original-title="Christina Parker">
							<img src="http://template.test/assets/img/avatars/7.png" alt="Avatar" class="rounded-circle">
							</li>
						</ul>
					</td>
					<td><span class="badge bg-label-primary me-1">Active</span></td>
					<td>
						<div class="dropdown">
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
							<div class="dropdown-menu">
								<a class="dropdown-item" onclick="showModal('Edit')"><i class="bx bx-edit-alt me-1"></i> Edit</a>
								<a class="dropdown-item" onclick="showModal('Delete')"><i class="bx bx-trash me-1"></i> Delete</a>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
    </div>
</div>

<div class="modal fade" id="backDropModal" data-bs-backdrop="static" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<form class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="backDropModalTitle"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Show a second modal and hide this one with the button below.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save</button>
			</div>
		</form>
	</div>
</div>
@endsection


@section('page-script')
	<script>
		function showModal(title){
			$('#backDropModal #backDropModalTitle').text(title)
			$('#backDropModal').modal('show')
		}
	</script>	
@endsection