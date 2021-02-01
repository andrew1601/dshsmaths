const Ziggy = {"url":"https:\/\/dshsmaths.dev","port":null,"defaults":{},"routes":{"arbor-import.index":{"uri":"arbor-import","methods":["GET","HEAD"]},"teaching-groups.index":{"uri":"teaching-groups","methods":["GET","HEAD"]},"teaching-groups.show":{"uri":"teaching-groups\/{teaching_group}","methods":["GET","HEAD"],"bindings":{"teaching_group":"id"}},"tests.index":{"uri":"tests","methods":["GET","HEAD"]},"tests.create":{"uri":"tests\/create","methods":["GET","HEAD"]},"tests.show":{"uri":"tests\/{test}","methods":["GET","HEAD"],"bindings":{"test":"id"}},"tests.assign.show":{"uri":"tests\/{test}\/assign","methods":["GET","HEAD"],"bindings":{"test":"id"}},"tests.assign":{"uri":"tests\/{test}\/assign","methods":["PATCH"],"bindings":{"test":"id"}},"tests.assign.delete":{"uri":"tests\/{test}\/assign","methods":["DELETE"],"bindings":{"test":"id"}},"data-entry.index":{"uri":"data-entry","methods":["GET","HEAD"]},"data-entry.spreadsheet.show":{"uri":"data-entry\/paper\/{paper}\/teaching-group\/{teaching_group}","methods":["GET","HEAD"],"bindings":{"paper":"id","teaching_group":"id"}},"analysis.index":{"uri":"analysis","methods":["GET","HEAD"]},"analysis.show":{"uri":"analysis\/test\/{test}\/teaching-group\/{teaching_group}\/student\/{student}","methods":["GET","HEAD"],"bindings":{"test":"id","teaching_group":"id","student":"id"}},"cohorts.index":{"uri":"cohorts","methods":["GET","HEAD"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    for (let name in window.Ziggy.routes) {
        Ziggy.routes[name] = window.Ziggy.routes[name];
    }
}

export { Ziggy };
