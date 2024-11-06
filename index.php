<?php
    session_start();

    $initial_balance = isset($_SESSION['initial_balance']) ? $_SESSION['initial_balance'] : 100;

    class Lucky7 {
        public $balance = 100;

        public function initial_balance($balance) {
            $this->balance = $balance;
        }

        public function placeBet() {
            $dice1 = rand(1, 6);
            $dice2 = rand(1, 6);
            $total = $dice1 + $dice2;

            return ['total'=>$total, 'dice1'=>$dice1, 'dice2'=>$dice2];
        }
    }

    $object = new Lucky7();

    $object->initial_balance($initial_balance);

    $_SESSION['initial_balance'] = $object->balance;

    $message = '';
    $placeBet = ['total'=>'', 'dice1'=>'', 'dice2'=>''];

    if ($_GET['action']=='bet') {
        $placeBet = $object->placeBet();
        $total = $placeBet['total'];
        $object->balance = $object->balance - 10;
        $_SESSION['initial_balance'] = $object->balance;

        $bet = $_GET['bet'];

        if ($bet=='below-7') {
            if ($total < 7) {
                $object->balance = $object->balance + 20;
                $_SESSION['initial_balance'] = $object->balance;
                $message = 'Congratulations! You win! Your balance is now ' . $object->balance . ' Rs.';
            }
            else {
                $message = 'Sorry! You lost! Your balance is now ' . $object->balance . ' Rs.';
            }
        }
        else if ($bet=='above-7') {
            if ($total > 7) {
                $object->balance = $object->balance + 20;
                $_SESSION['initial_balance'] = $object->balance;
                $message = 'Congratulations! You win! Your balance is now ' . $object->balance . ' Rs.';
            }
            else {
                $message = 'Sorry! You lost! Your balance is now ' . $object->balance . ' Rs.';
            }
        }
        else if ($bet=='7') {
            if ($total == 7) {
                $object->balance = $object->balance + 30;
                $_SESSION['initial_balance'] = $object->balance;
                $message = 'Congratulations! You win! Your balance is now ' . $object->balance . ' Rs.';
            }
            else {
                $message = 'Sorry! You lost! Your balance is now ' . $object->balance . ' Rs.';
            }
        }
    }
    else if ($_GET['action']=='reset') {
        unset($_SESSION['initial_balance']);
        header("location:index.php");exit;
    }
    else if ($_GET['action']=='continue') {
        header("location:index.php");exit;
    }


?>
<html>
    <head>
        <title>Lucky 7</title>
    </head>
    <body>
        <h1>Welcome to lucky 7 game</h1>
        <?php
            if ($_GET['action']=='') {
                if ($object->balance<10) {
                    ?>
                    <p>Sorry! you don't have enough balance! your balance is now <?php echo $object->balance; ?> Rs.</p>
                    <form action="" method="get">
                        <button type="submit" name="action" value="reset">Reset</button>
                    </form>
                    <?php
                }
                else {
                    ?>
                    <p>Current Balance: <?php echo $object->balance; ?></p>
                    <p>Place your bet (Rs 10): <br />
                    <form action="" method="get">
                        <input type="radio" name="bet" value="below-7"> Below 7
                        <input type="radio" name="bet" value="7"> 7
                        <input type="radio" name="bet" value="above-7">Above 7
                        <br /><br />
                        <input type="hidden" name="action" value="bet" />
                        <button type="submit">Play</button>
                    </form>
                    <?php
                }
            }
            else if ($_GET['action']=='bet') {
                ?>
                <p>Game results:</p>
                <p>Dice 1: <?php echo $placeBet['dice1']; ?></p>
                <p>Dice 2: <?php echo $placeBet['dice2']; ?></p>
                <p>Total: <?php echo $placeBet['total']; ?></p>

                <br />

                <p><?php echo $message; ?></p>

                <form action="" method="get">
                    <button type="submit" name="action" value="reset">Reset and Play Again</button>
                    <button type="submit" name="action" value="continue">Continue Playing</button>
                </form>
            <?php
            }
        ?>
    </body>
</html>