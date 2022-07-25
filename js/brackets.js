!(function (t) {
  var e = {};
  function a(n) {
    if (e[n]) return e[n].exports;
    var o = (e[n] = { i: n, l: !1, exports: {} });
    return t[n].call(o.exports, o, o.exports, a), (o.l = !0), o.exports;
  }
  (a.m = t),
    (a.c = e),
    (a.d = function (t, e, n) {
      a.o(t, e) || Object.defineProperty(t, e, { enumerable: !0, get: n });
    }),
    (a.r = function (t) {
      "undefined" != typeof Symbol &&
        Symbol.toStringTag &&
        Object.defineProperty(t, Symbol.toStringTag, { value: "Module" }),
        Object.defineProperty(t, "__esModule", { value: !0 });
    }),
    (a.t = function (t, e) {
      if ((1 & e && (t = a(t)), 8 & e)) return t;
      if (4 & e && "object" == typeof t && t && t.__esModule) return t;
      var n = Object.create(null);
      if (
        (a.r(n),
        Object.defineProperty(n, "default", { enumerable: !0, value: t }),
        2 & e && "string" != typeof t)
      )
        for (var o in t)
          a.d(
            n,
            o,
            function (e) {
              return t[e];
            }.bind(null, o)
          );
      return n;
    }),
    (a.n = function (t) {
      var e =
        t && t.__esModule
          ? function () {
              return t.default;
            }
          : function () {
              return t;
            };
      return a.d(e, "a", e), e;
    }),
    (a.o = function (t, e) {
      return Object.prototype.hasOwnProperty.call(t, e);
    }),
    (a.p = ""),
    a((a.s = 0));
})([
  function (t, e, a) {
    t.exports = a(1);
  },
  function (t, e) {
    !(function () {
      "use strict";
      var t = simple_tournament_brackets_options;
      window.addEventListener(
        "load",
        function () {
          function e(t) {
            var e = "competitor-".concat(t.target.dataset.competitorId);
            Array.from(document.getElementsByClassName(e)).forEach(function (
              t
            ) {
              t.classList.add(
                "simple-tournament-brackets-competitor-highlight"
              );
            });
          }
          function a(t) {
            var e = "competitor-".concat(t.target.dataset.competitorId);
            Array.from(document.getElementsByClassName(e)).forEach(function (
              t
            ) {
              t.classList.remove(
                "simple-tournament-brackets-competitor-highlight"
              );
            });
          }
          function n(e, a, n) {
            var o = "",
              c = n < e.competitors.length / 2;
            if (
              e.matches[n] &&
              (null !== e.matches[n].one_id || null !== e.matches[n].two_id)
            ) {
              if (
                ((o += '<div class="dropdown">'),
                (o +=
                  '<span class="more-details dashicons dashicons-admin-generic"></span>'),
                (o += '<div class="dropdown-content" >'),
                e.matches[n] && null !== e.matches[n].one_id)
              ) {
                var r = e.matches[n].one_id;
                o +=
                  '<a href="#" class="advance-competitor" data-tournament-id="'
                    .concat(a, '" data-match-id="')
                    .concat(n, '" data-competitor-id="')
                    .concat(r, '">')
                    .concat(
                      t.language.advance.replace(
                        "{NAME}",
                        e.competitors[r].name
                      ),
                      "</a>"
                    );
              }
              if (e.matches[n] && null !== e.matches[n].two_id) {
                var s = e.matches[n].two_id;
                o +=
                  '<a href="#" class="advance-competitor" data-tournament-id="'
                    .concat(a, '" data-match-id="')
                    .concat(n, '" data-competitor-id="')
                    .concat(s, '">')
                    .concat(
                      t.language.advance.replace(
                        "{NAME}",
                        e.competitors[s].name
                      ),
                      "</a>"
                    );
              }
              c ||
                (o +=
                  '<a href="#" class="clear-competitors" data-tournament-id="'
                    .concat(a, '" data-match-id="')
                    .concat(n, '">')
                    .concat(t.language.clear, "</a>")),
                (o += "</div>"),
                (o += "</div>");
            }
            return o;
          }
          function o(t, e, a, o, c) {
            var r = "";
            if (
              ((r += '<div class="simple-tournament-brackets-match">'),
              (r += '<div class="horizontal-line"></div>'),
              (r += '<div class="simple-tournament-brackets-match-body">'),
              t.matches[a] && null !== t.matches[a].one_id)
            ) {
              var s = t.matches[a].one_id,
                i = t.competitors[s] ? t.competitors[s].name : "&nbsp;";
              r +=
                '<span class="simple-tournament-brackets-competitor competitor-'
                  .concat(s, '" data-competitor-id="')
                  .concat(s, '">')
                  .concat(i, "</span>");
            } else
              r +=
                '<span class="simple-tournament-brackets-competitor">&nbsp;</span>';
            if (t.matches[a] && null !== t.matches[a].two_id) {
              var m = t.matches[a] ? t.matches[a].two_id : null,
                d = t.matches[a] ? t.competitors[m].name : "&nbsp;";
              r +=
                '<span class="simple-tournament-brackets-competitor competitor-'
                  .concat(m, '" data-competitor-id="')
                  .concat(m, '">')
                  .concat(d, "</span>");
            } else
              r +=
                '<span class="simple-tournament-brackets-competitor">&nbsp;</span>';
            return (
              (r += "</div>"),
              o &&
                ((r +=
                  1 == a % 2
                    ? '<div class="bottom-half">'
                    : '<div class="top-half">'),
                c && (r += n(t, e, a)),
                (r += "</div>")),
              (r += "</div>")
            );
          }
          function c(c, r, s) {
            var i,
              m,
              d,
              l = "";
            l +=
              '<div class="simple-tournament-brackets-round-header-container">';
            for (var u = 0; u <= c.rounds; u++)
              l +=
                '<span class="simple-tournament-brackets-round-header">'.concat(
                  t.language.rounds[u],
                  "</span>"
                );
            (l += "</div>"),
              (l +=
                ((d = (function (t) {
                  for (
                    var e = t.competitors.length - 1,
                      a = 0,
                      n = t.competitors.length / 2;
                    n <= t.competitors.length;
                    n++
                  )
                    t.matches[n] &&
                      (null !== t.matches[n].one_id && a++,
                      null !== t.matches[n].two_id && a++);
                  return a / e;
                })(c)),
                '<div class="simple-tournament-brackets-progress" style="width: '.concat(
                  100 * d,
                  '%;">&nbsp;</div> '
                ))),
              (l +=
                '<div class="simple-tournament-brackets-round-body-container">');
            for (var p = 1, f = 0, h = 1; h <= c.rounds; h++) {
              for (
                i = Math.ceil(c.competitors.length / Math.pow(2, h)),
                  m = Math.pow(2, h) - 1,
                  l += '<div class="simple-tournament-brackets-round-body">';
                p <= i + f;
                p++
              ) {
                for (var v = 0; v < m; v++)
                  l +=
                    1 == p % 2
                      ? '<div class="match-half">&nbsp;</div> '
                      : '<div class="vertical-line">&nbsp;</div> ';
                l += o(c, s, p - 1, h !== c.rounds, t.can_edit_matches);
                for (var b = 0; b < m; b++)
                  h !== c.rounds && 1 == p % 2
                    ? (l += '<div class="vertical-line">&nbsp;</div> ')
                    : (l += '<div class="match-half">&nbsp;</div> ');
              }
              (l += "</div>"), (f += i);
            }
            l += '<div class="simple-tournament-brackets-round-body">';
            for (var g = 0; g < m; g++)
              l += '<div class="match-half">&nbsp;</div> ';
            if (
              ((l += '<div class="simple-tournament-brackets-match">'),
              (l += '<div class="winners-line">'),
              t.can_edit_matches && (l += n(c, s, p - 2)),
              (l += "</div>"),
              (l += '<div class="simple-tournament-brackets-match-body">'),
              (l +=
                '<span class="simple-tournament-brackets-competitor"><strong>'.concat(
                  t.language.winner,
                  "</strong></span>"
                )),
              c.matches[p - 1] && null !== c.matches[p - 1].one_id)
            ) {
              var y = c.matches[p - 1].one_id;
              l +=
                '<span class="simple-tournament-brackets-competitor competitor-'
                  .concat(y, '" data-competitor-id="')
                  .concat(y, '">')
                  .concat(c.competitors[y].name, "</span>");
            } else
              l +=
                '<span class="simple-tournament-brackets-competitor">&nbsp;</span>';
            (l += "</div>"), (l += "</div>");
            for (var _ = 0; _ < m; _++)
              l += '<div class="match-half">&nbsp;</div> ';
            (l += "</div>"),
              (l += "</div>"),
              (r.innerHTML = l),
              Array.from(
                document.getElementsByClassName(
                  "simple-tournament-brackets-competitor"
                )
              ).forEach(function (t) {
                t.addEventListener("mouseover", e),
                  t.addEventListener("mouseleave", a);
              }),
              Array.from(
                document.getElementsByClassName("advance-competitor")
              ).forEach(function (e) {
                e.addEventListener("click", function (e) {
                  e.preventDefault(),
                    (function (e, a, n) {
                      return fetch(
                        "".concat(
                          t.site_url,
                          "/wp-json/simple-tournament-brackets/v1/tournament-matches/advance"
                        ),
                        {
                          headers: {
                            "Content-Type": "application/json; charset=utf-8",
                            "X-WP-Nonce": t.rest_nonce,
                          },
                          method: "POST",
                          body: JSON.stringify({
                            id: a,
                            tournament_id: e,
                            winner_id: n,
                          }),
                        }
                      ).then(function (t) {
                        return t.json();
                      });
                    })(
                      e.target.dataset.tournamentId,
                      e.target.dataset.matchId,
                      e.target.dataset.competitorId
                    ).then(function () {
                      location.reload();
                    });
                });
              }),
              Array.from(
                document.getElementsByClassName("clear-competitors")
              ).forEach(function (e) {
                e.addEventListener("click", function (e) {
                  e.preventDefault(),
                    (function (e, a) {
                      return fetch(
                        "".concat(
                          t.site_url,
                          "/wp-json/simple-tournament-brackets/v1/tournament-matches/clear"
                        ),
                        {
                          headers: {
                            "Content-Type": "application/json; charset=utf-8",
                            "X-WP-Nonce": t.rest_nonce,
                          },
                          method: "POST",
                          body: JSON.stringify({ id: a, tournament_id: e }),
                        }
                      ).then(function (t) {
                        return t.json();
                      });
                    })(
                      e.target.dataset.tournamentId,
                      e.target.dataset.matchId
                    ).then(function () {
                      location.reload();
                    });
                });
              });
          }
          Array.from(
            document.getElementsByClassName("simple-tournament-brackets")
          ).forEach(function (e) {
            var a;
            ((a = e.dataset.tournamentId),
            fetch(
              "".concat(t.site_url, "/wp-json/wp/v2/stb-tournament/").concat(a),
              { headers: { "Content-Type": "application/json; charset=utf-8" } }
            ).then(function (t) {
              return t.json();
            })).then(function (t) {
              c(t.stb_match_data, e, e.dataset.tournamentId);
            });
          });
        },
        !1
      );
    })();
  },
]);
