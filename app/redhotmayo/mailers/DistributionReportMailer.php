<?php namespace redhotmayo\mailers;

class DistributionReportMailer extends Mailer {

    public function send($report, $emailAddresses) {
        $view = 'emails.distribution.distributionReport';
        $data = [ 'report'	=> $report ];
        $subject = 'Lead Distribution Report';

        return $this->sendTo($emailAddresses, $subject, $view, $data);
    }
}