self.addEventListener("push", function(event) {
    const data = event.data.json();

    const options = {
        body: data.body,
        icon: data.icon || "/assets/images/logos/edulk-logo.png",
        badge: "/assets/images/logos/edulk-logo.png",
        vibrate: [200, 100, 200],
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});
 