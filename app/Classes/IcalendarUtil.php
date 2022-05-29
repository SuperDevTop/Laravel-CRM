<?php

/**
 * iCalendar util
 */
class IcalendarUtil
{

    /**
     * Generates a iCalendar event.
     *
     * @param array $organizer
     * @param DateTime $from_date
     * @param DateTime $to_date
     * @param array $attendees
     * @param string $subject
     * @param string $location
     * @param string $description
     * @param bool $all_day_event
     * @param bool $rsvp
     * @return string
     * @static
     * @throws cbmException
     */
    static public function genEvent(array $organizer, DateTime $from_date, DateTime $to_date = null, array $attendees = null, $subject = null, $location = null, $description = null, $all_day_event = false, $rsvp = true)
    {
        if (!$all_day_event && $to_date === null)
        {
            throw new Exception('to_date is required when the event is not an all day event');
        }
        $organizer_name = $organizer[0];
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//Microsoft Corporation//Outlook 14.0 MIMEDIR//EN\r\n";
        $ical .= "METHOD:REQUEST\r\n";
        $ical .= "X-MS-OLK-FORCEINSPECTOROPEN:TRUE\r\n";
        $ical .= "BEGIN:VTIMEZONE\r\n";
        $ical .= sprintf("TZID:%s\r\n", date('T'));
        $ical .= "BEGIN:STANDARD\r\n";
        $ical .= "DTSTART:16010101T000000\r\n";
        $ical .= "TZOFFSETFROM:+0545\r\n";
        $ical .= "TZOFFSETTO:+0545\r\n";
        $ical .= "END:STANDARD\r\n";
        $ical .= "END:VTIMEZONE\r\n";
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= sprintf("UID:%s\r\n", rand());

        $ical .= sprintf("ORGANIZER;CN=\"%s\":MAILTO:%s\r\n", $organizer_name, $organizer[1]);
        foreach ($attendees as $email => $name)
        {
            $ical .= sprintf("ATTENDEE;");
            if ($name !== null)
            {
                $ical .= sprintf("CN=\"%s\";", $name);
            }
            if($rsvp)
            {
                $ical .= sprintf("RSVP=TRUE:");
            }
            $ical .= sprintf("mailto:%s;\r\n", $email);
        }
        $ical .= sprintf("LOCATION:%s\r\n", $location);
        $ical .= sprintf("DTSTAMP:%s\r\n", self::getDateTimeInUTCFormat());
        $ical .= "CLASS:PUBLIC\r\n";
        $ical .= sprintf("DTSTART:%s\r\n", self::getDateTimeInUTCFormat($from_date));
        if (!$all_day_event)
        {
            $ical .= sprintf("DTEND:%s\r\n", self::getDateTimeInUTCFormat($to_date));
        }
        $ical .= sprintf("SUMMARY:%s\r\n", $subject);
        $ical .= "TRANSP:OPAQUE\r\n";
        $ical .= "X-MICROSOFT-CDO-BUSYSTATUS:TENTATIVE\r\n";
        $ical .= "X-MICROSOFT-CDO-IMPORTANCE:1\r\n";
        $ical .= "X-MICROSOFT-CDO-INTENDEDSTATUS:BUSY\r\n";
        $ical .= "X-MICROSOFT-DISALLOW-COUNTER:FALSE\r\n";
        $ical .= "X-MS-OLK-APPTLASTSEQUENCE:1\r\n";
        $ical .= "X-MS-OLK-AUTOSTARTCHECK:FALSE\r\n";
        $ical .= "X-MS-OLK-CONFTYPE:0\r\n";
        $ical .= sprintf("X-MS-OLK-SENDER;CN=\"%s\":MAILTO:%s\r\n", $organizer_name, $organizer[1]);
        $ical .= sprintf("X-ALT-DESC;FMTTYPE=text/html:%s\r\n", "<p>" . preg_replace('/\R/', "</p><p>", $description) . "</p>");
        $ical .= sprintf("X-MS-OLK-CONFTYPE:0\r\n");
        $ical .= "BEGIN:VALARM\r\n";
        $ical .= "TRIGGER:-PT15M\r\n";
        $ical .= "ACTION:DISPLAY\r\n";
        $ical .= "DESCRIPTION:Reminder\r\n";
        $ical .= "END:VALARM\r\n";
        $ical .= "END:VEVENT\r\n";
        $ical .= "END:VCALENDAR";
        return $ical;
    }

    static protected function getDateTimeInUTCFormat(DateTime $date = null)
    {
        if ($date === null)
        {
            $date = new DateTime();
        }
        $date->setTimezone(new DateTimeZone('UTC'));
        return $date->format('Ymd\THis\Z');
    }

}