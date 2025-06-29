<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../..');

require 'add/bad/dad/dev.php';
require 'add/bad/error.php';
require 'add/bad/io.php';
require 'add/bad/db.php';
require 'add/bad/auth.php';

require 'app/morph/html.php';


// dbq(db(),"INSERT INTO operator (label, email, password_hash, status) VALUES (?, ?, ?, 1)",['jp', 'admin@domain.com', password_hash('jp', PASSWORD_DEFAULT)]);
try {
    auth(AUTH_SETUP, 'operator.username', "SELECT `password_hash` FROM `operator` WHERE `username` = ?");

    $io         = realpath(__DIR__ . '/../io');
    $re_quest   = http_in(4096, 9);

    // coding: find the route and invoke it
    $in_route   = io_route("$io/route", $re_quest, 'index');
    $in_quest   = io_quest($in_route, [], IO_INVOKE);
    // render: find the render file and absorb it
    $out_route  = io_route("$io/render/", $re_quest, 'index');
    $out_quest  = io_quest($out_route, $in_quest[IO_INVOKE], IO_ABSORB);

    if (is_string($out_quest[IO_ABSORB])) {
        http_out(200, $out_quest[IO_ABSORB], ['Content-Type' => 'text/html; charset=utf-8']);
        exit;
    }

    error_log('404 Not Found for ' . $re_quest . ' in ' . $io);

    http_out(404, 'Not Found at all');
} catch (LogicException | RuntimeException $t) {
    handle_badhat_exception($t);
    if (function_exists('is_dev')) {
        vd(0, $t);
        exit;
    }
    // otherwise we just log it
    error_log($t->getMessage() . ' in ' . $io . ' at ' . $re_quest);
    // and return a generic error

    header('HTTP/1.1 500 Forbidden');
} catch (Throwable $t) {
    vd(0, $t);
    die;
}

function handle_badhat_exception(Throwable $t): void
{
    if ((int)$t->getCode() === 403) {
        $u = parse_url($_SERVER['HTTP_REFERER'] ?? '');
        parse_str($u['query'] ?? '', $q);

        if (isset($q['error'])) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $q['error'] = $t->getMessage();
        header('Location: ' . ($u['path'] ?: '/') . '?' . http_build_query($q) . (isset($u['fragment']) ? "#{$u['fragment']}" : ''));
        exit;
    }

    if ((int)$t->getCode() === 401) {
        header('Location: /login');
        exit;
    }
}
