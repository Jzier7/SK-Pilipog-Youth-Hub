export default {
  methods: {
    formatDate(date, format) {
      const options = {};
      const formatParts = format.split(' ');

      formatParts.forEach(part => {
        switch (part) {
          case 'MM':
            options.month = '2-digit';
            break;
          case 'MMM':
            options.month = 'short';
            break;
          case 'MMMM':
            options.month = 'long';
            break;
          case 'DD':
            options.day = '2-digit';
            break;
          case 'D':
            options.day = 'numeric';
            break;
          case 'YYYY':
            options.year = 'numeric';
            break;
          case 'YY':
            options.year = '2-digit';
            break;
          case 'h':
            options.hour = 'numeric';
            options.hour12 = true; // 12-hour format
            break;
          case 'mm':
            options.minute = '2-digit';
            break;
          case 'a':
            options.hour12 = true; // 12-hour format for AM/PM
            break;
          default:
            break;
        }
      });

      const datePart = new Date(date).toLocaleString('en-US', options);
      const timePart = this.formatTime(date);

      return `${datePart} at ${timePart}`;
    },
    formatTime(date) {
      const options = {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      };
      return new Date(date).toLocaleString('en-US', options).replace(',', '');
    },
    timeAgo(date) {
      const now = new Date();
      const announcementDate = new Date(date);
      const seconds = Math.floor((now - announcementDate) / 1000);
      const oneWeekInSeconds = 604800; // 7 days
      const oneYearInSeconds = 31536000; // 365 days

      if (seconds < oneWeekInSeconds) {
        let interval = Math.floor(seconds / 31536000); // Years
        if (interval >= 1) return interval + " year" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 2592000); // Months
        if (interval >= 1) return interval + " month" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 86400); // Days
        if (interval >= 1) return interval + " day" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 3600); // Hours
        if (interval >= 1) return interval + " hour" + (interval > 1 ? "s" : "") + " ago";
        interval = Math.floor(seconds / 60); // Minutes
        if (interval >= 1) return interval + " minute" + (interval > 1 ? "s" : "") + " ago";
        return "just now";
      } else if (seconds < oneYearInSeconds) {
        return this.formatDate(announcementDate, 'D MMMM YYYY');
      } else {
        return this.formatDate(announcementDate, 'D MMMM YYYY');
      }
    },
  },
};

