<extends:sendit:builder subject="Tickets for movie"/>
<use:bundle path="sendit:bundle"/>

<block:html>
    <p>Hello, {{ $email }}!</p>
    <p>Auditorium {{ $auditorium }}</p>
    <p>Starts at {{ $startsAt }}</p>
    <p>Seats {{ $seats }}</p>
</block:html>
