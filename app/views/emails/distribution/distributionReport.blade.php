<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <title>Distribution Report</title>
        <style>
			th {
				background-color: black;
				color: white;
			}
		</style>
    </head>
    <body>
		<h2>Distribution Report</h2>
		<p/>
		<hr/>
		<div id="summary">
		<?php
			$summary = $report->getSummary();
			$details = $report->getDetails();
		?>
			<h3>Summary</h3>
			<hr/>
			<table>
				<tr><td>Date Processed:</td><td>{{ $summary->getProcessedDate()->format('Y-m-d H:i:s') }}</td><tr>
				<?php $processedFiles = $summary->getProcessedFiles(); ?>
				<tr><td>Processed Files:</td><td>{{{ (isset($processedFiles) && count($processedFiles) > 0) ? implode(', ', $processedFiles) : 'None' }}}</td></tr>
				<?php $unprocessedFiles = $summary->getUnprocessedFiles(); ?>
				<tr><td>Unprocessed Files:</td><td>{{{ (isset($unprocessedFiles) && count($unprocessedFiles) > 0) ? implode(', ', $unprocessedFiles) : 'None' }}}</td></tr>
			</table>
			<p/>
			<table border="1" cellpadding="5" cellspacing="5">
				<tr><th>Metric</th><th>Count</th><th>Percentage</th></tr>
				<tr><td>Files Detected</td><td>{{ $summary->getFilesDetected() }}</td><td></td></tr>
				<tr><td>Files Processed</td><td>{{ $summary->getFilesProcessed() }}</td><td></td></tr>
				<tr><td>Files Unprocessed</td><td>{{ $summary->getFilesUnprocessed() }}</td><td></td></tr>
				<tr><td>Leads Detected</td><td>{{ $summary->getLeadsDetected() }}</td><td></td></tr>
				<tr><td>Leads Processed</td><td>{{ $summary->getLeadsProcessed() }}</td><td>{{ number_format($summary->getLeadsProcessedPercentage() * 100, 2) . '%' }}</td></tr>
				<tr><td>Leads Unprocessed</td><td>{{ $summary->getLeadsUnprocessed() }}</td><td>{{ number_format($summary->getLeadsUnprocessedPercentage() * 100, 2) . '%' }}</td></tr>
				<tr><td>Leads Unsaved:</td><td>{{ $summary->getLeadsUnsaved() }}</td><td>{{ number_format($summary->getLeadsUnsavedPercentage() * 100, 2) . '%' }}</td></tr>
				<tr><td>Leads Undistributed</td><td>{{ $summary->getLeadsUndistributed() }}</td><td>{{ number_format($summary->getLeadsUndistributedPercentage() * 100, 2) . '%' }}</td></tr>
			</table>
		</div>
		<p/>
		<hr/>
		@if (isset($details) && count($details) > 0)
			<div id="detail">
				<h3>Detail</h3>
				<hr/>
				<?php $count = 1; ?>
				@foreach ($details as $detail)
					<div id="detail-filename-{{ $count }}">
						<h4>Filename: {{ $detail->getFilename() }}</h4>
						<table border="1" cellpadding="5" cellspacing="5">
							<tr><th>Metric</th><th>Count</th><th>Percentage</th></tr>
							<tr><td>Leads Detected</td><td>{{ $detail->getLeadsDetected() }}</td><td></td></tr>
							<tr><td>Leads Processed</td><td>{{ $detail->getLeadsProcessed() }}</td><td>{{ number_format($detail->getLeadsProcessedPercentage() * 100, 2) . '%' }}</td></tr>
							<tr><td>Leads Unprocessed</td><td>{{ $detail->getLeadsUnprocessed() }}</td><td>{{ number_format($detail->getLeadsUnprocessedPercentage() * 100, 2) . '%' }}</td></tr>
							<tr><td>Leads Unsaved:</td><td>{{ $detail->getLeadsUnsaved() }}</td><td>{{ number_format($detail->getLeadsUnsavedPercentage() * 100, 2) . '%' }}</td></tr>
							<tr><td>Leads Undistributed</td><td>{{ $detail->getLeadsUndistributed() }}</td><td>{{ number_format($detail->getLeadsUndistributedPercentage() * 100, 2) . '%' }}</td></tr>
						</table>
					</div>
					<?php $unsavedLeads = $detail->getUnsavedLeads(); ?>
					@if (isset($unsavedLeads) && count($unsavedLeads) > 0)
					<h4>Unsaved Leads</h4>
					<div id="detail-unsaved-{{ $count }}">
						<table border="1" cellpadding="5" cellspacing="5">
							<tr><th>Account Name</th></tr>
						@foreach ($unsavedLeads as $unsavedLead)
							<tr>
								<td>{{ $unsavedLead->getAccountName() }}</td>
							</tr>
						@endforeach
						</table>
					</div>
					<p/>
					@endif
					<?php $undistributedLeads = $detail->getUndistributedLeads(); ?>
					@if (isset($undistributedLeads) && count($undistributedLeads) > 0)
					<h4>Undistributed Leads</h4>
					<div id="detail-undistributed-{{ $count }}">
						<table border="1" cellpadding="5" cellspacing="5">
							<tr><th>Account Name</th></tr>
						@foreach ($undistributedLeads as $undistributedLead)
							<tr>
								<td>{{ $undistributedLead->getAccountName() }}</td>
							</tr>
						@endforeach
						</table>
					</div>
					<p/>
					@endif
					<?php $count++; ?>
					<hr/>
				@endforeach
			</div>
		@endif
    </body>
</html>