<br>

<div style="border:1px solid #e5e7eb; padding:12px; border-radius:8px; margin-bottom:15px; background:#f9fafb;">

	<div style="font-weight:600; font-size:16px; color:#111827;">
		<?= ucwords($user_data['first_name'] . ' ' . $user_data['last_name']); ?>
	</div>

	<div style="font-size:13px; color:#6b7280; margin-top:4px;">
		📧 <?= $user_data['email']; ?>
	</div>

	<div style="font-size:13px; color:#6b7280;">
		📱 <?= $user_data['mobile']; ?>
	</div>

	<div style="font-size:13px; color:#6b7280;">
		💳 <?= $user_data['payment_method'] . ": " . $user_data['payment_info']; ?>
	</div>

	<div class="mb-3">
		<span class="text-muted mr-2">Total Paid:</span>
		<span class="badge badge-success p-2">
			$<?= number_format($total_paid, 2); ?>
		</span>
	</div>

</div>

<table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
	<thead>
		<tr>
			<td width="2%" align="center"><b>Sr.No</b></td>
			<td align="center"><b>Date</b></td>
			<td align="center"><b>Payment Method</b></td>
			<td align="center"><b>Amount($)</b></td>
			<td align="center"><b>Action</b></td>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 0;
		foreach ($db_list as $d) {
			$i++;

		?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?= showdate($d['date']); ?></td>
				<td><?php echo $d["payment_method"]; ?></td>
				<td>$<?php echo number_format($d["amount"], 2); ?></td>
				<td align="center">
					<button type="button"
						class="btn btn-sm btn-danger delete-btn"
						data-id="<?= $d['id']; ?>"
						data-user_id="<?= $d['user_id']; ?>">
						🗑 Delete
					</button>
				</td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>

<div class="modal fade" id="deleteConfirmModal">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">

			<form method="POST" action="referrer-payout?user_id=<?= $_GET['user_id']; ?>">

				<div class="modal-header">
					<h5 class="modal-title">Confirm Deletion</h5>
				</div>

				<div class="modal-body">
					<p class="text-muted">
						Enter secret code to delete this payout record.
					</p>

					<input type="password" name="password"
						class="form-control"
						placeholder="Enter secret code" required>

					<input type="hidden" name="id" id="deleteId">
					<input type="hidden" name="user_id" id="deleteUserId">
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger">
						🗑 Confirm Delete
					</button>
				</div>

			</form>

		</div>
	</div>
</div>

<?php
if ($total > 100) {
	echo "<ul class='pagination pull-right'>" . paging($total, 100, $param) . "</ul>";
}
?>

<script>
	$('.delete-btn').on('click', function() {
		let id = $(this).data('id');
		let userId = $(this).data('user_id');

		$('#deleteId').val(id);
		$('#deleteUserId').val(userId);

		$('#deleteConfirmModal').modal('show');
	});
</script>