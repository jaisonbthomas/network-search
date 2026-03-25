(function($) {
    'use strict';

    $(document).ready(function() {
        $('.pfl-wrap').each(function() {
            const wrap = $(this);
            const form = wrap.find('.pfl-form');
            const out = wrap.find('.pfl-out');
            const spin = wrap.find('.pfl-spin');
            const input = wrap.find('input[name="query"]');

            function esc(s) {
                return String(s).replace(/[&<>"']/g, function(m) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    }[m];
                });
            }

            async function search(q) {
                if (!q) {
                    out.html('');
                    return;
                }
                spin.show();
                try {
                    const res = await fetch(pfl_ajax.ajax_url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'pfl_lookup',
                            nonce: pfl_ajax.nonce,
                            query: q
                        })
                    });
                    const text = await res.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (_e) {
                        spin.hide();
                        out.html('<p>' + pfl_ajax.strings.server_error + '<br><code>' + esc(text) + '</code></p>');
                        return;
                    }
                    spin.hide();
                    if (!data.success) {
                        out.html('<p>' + pfl_ajax.strings.something_wrong + '</p>');
                        return;
                    }
                    const items = data.data.items || [];
                    if (items.length === 0) {
                        out.html('<p><strong>' + pfl_ajax.strings.not_in_delivery_zone + '</strong> ' + pfl_ajax.strings.for + ' <strong>' + esc(q) + '</strong> <span class="pfl-badge no">' + pfl_ajax.strings.no + '</span></p>');
                        return;
                    }

                    // Delivery decision: if ANY row's zone contains 'non' (e.g., Non-Delivery), mark as No; else Yes
                    const non = items.some(r => String(r.zone || '').toLowerCase().includes('non'));
                    let html = '';
                    html += '<p><strong>' + (non ? pfl_ajax.strings.not_in_delivery_zone : pfl_ajax.strings.delivery_available) + '</strong> ' + pfl_ajax.strings.for + ' <strong>' + esc(q) + '</strong> ';
                    html += '<span class="pfl-badge ' + (non ? 'no' : 'yes') + '">' + (non ? pfl_ajax.strings.no : pfl_ajax.strings.yes) + '</span> ';
                    html += '<em>(' + items.length + ' ' + (items.length > 1 ? pfl_ajax.strings.results : pfl_ajax.strings.result) + ')</em></p>';

                    html += '<table class="pfl-table"><thead><tr><th>' + pfl_ajax.strings.pincode + '</th><th>' + pfl_ajax.strings.city + '</th><th>' + pfl_ajax.strings.state + '</th><th>' + pfl_ajax.strings.zone + '</th><th>' + pfl_ajax.strings.vendor + '</th><th>' + pfl_ajax.strings.ofd_tat + '</th><th>' + pfl_ajax.strings.location + '</th></tr></thead><tbody>';
                    html += items.map(i => '<tr>'
                        + '<td>' + esc(i.pincode || '') + '</td>'
                        + '<td>' + esc(i.city || '') + '</td>'
                        + '<td>' + esc(i.state || '') + '</td>'
                        + '<td>' + esc(i.zone || '') + '</td>'
                        + '<td>' + esc(i.vendor || '') + '</td>'
                        + '<td>' + esc(i.ofd_tat || '') + '</td>'
                        + '<td>' + esc(i.location || '') + '</td>'
                    + '</tr>').join('');
                    html += '</tbody></table>';
                    out.html(html);
                } catch (err) {
                    spin.hide();
                    out.html('<p>' + pfl_ajax.strings.error + ' ' + esc(err && err.message ? err.message : pfl_ajax.strings.request_failed) + '</p>');
                }
            }

            form.on('submit', function(e) {
                e.preventDefault();
                search(input.val().trim());
            });

            let t = null;
            input.on('keyup', function() {
                const q = input.val().trim();
                clearTimeout(t);
                t = setTimeout(() => search(q), 250);
            });
        });
    });

})(jQuery);