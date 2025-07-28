<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../..');

require 'add/build.php';
require 'add/error.php';
require 'add/io.php';
require 'add/db.php';
require 'add/auth.php';

require 'app/morph/html.php';


// vd(qp(db(),"INSERT INTO operator (label, username, password_hash, status) VALUES (?, ?, ?, 1)",['jp', 'jp', password_hash('jp', PASSWORD_DEFAULT)]));die;
try {
    auth(AUTH_SETUP, 'operator.username', "SELECT `password_hash` FROM `operator` WHERE `username` = ?");
    l(null, require 'app/lang/fr.php'); // Load French translations

    $re_quest   = http_in(4096, 9);
    $io = realpath(__DIR__ . '/../io');
    
    $request_admin = strpos($re_quest, '/admin') === 0;
    $request_admin && auth(AUTH_GUARD);

    // business: find the route and invoke it
    $in_path    = $io . '/route';
    $in_route   = io_route($in_path, $re_quest, IO_DEEP | IO_FLEX) ?: io_route($in_path, 'index');
    $in_quest   = io_fetch($in_route, [], IO_INVOKE);

    // render: find the render file and absorb it
    $out_path   = $io . '/render';
    
    $out_route  = io_route($out_path, $re_quest, IO_DEEP | IO_FLEX) ?: io_route($out_path, 'index');
    $out_quest  = io_fetch($out_route, $in_quest[IO_INVOKE], IO_ABSORB);
    if (is_string($out_quest[IO_OB_GET]) || is_string($out_quest[IO_ABSORB])) {
        if(isset($out_quest[IO_ABSORB])){
            $body = $out_quest[IO_ABSORB];
        }
        else{
            $template = $request_admin ? 'app/io/render/admin/layout.php' : 'app/io/render/layout.php';
            $body = ob_ret_get($template, ($args ?? []) + ['main' => $out_quest[IO_OB_GET]])[1];
        }
        // vd(-1, $body);
        http_out(200, $body, ['Content-Type' => 'text/html; charset=utf-8']);
        exit;
    }

    error_log('404 Not Found for ' . $re_quest);
    http_out(404, 'Not Found at all');
} catch (LogicException | RuntimeException $t) {
    handle_badhat_exception($t);
    if (function_exists('is_dev')) {
        vd(0, $t);
        exit;
    }
    error_log($t->getMessage() . ' in ' . $io . ' at ' . $re_quest);
    header('HTTP/1.1 500 Forbidden');
} catch (Throwable $t) {
    vd(0, $t);
    // out quest that fetch an error page within the layout, firsttests if error page has 200 
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
