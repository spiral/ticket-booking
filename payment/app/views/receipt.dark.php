<extends:sendit:builder subject="Receipt"/>
<use:bundle path="sendit:bundle"/>

<block:html>
    <p>Description {{ $description }}</p>
    <p>Hello, {{ $email }}!</p>
    <p>Transaction id {{ $transaction_id }}</p>
    <p>Amount {{ $amount }}</p>
    <p>Fee {{ $fee }}</p>
</block:html>
