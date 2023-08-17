 <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }

        .resume {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .hidden {
            display: none;
        }

        /* Header section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .name {
            font-size: 24px;
            font-weight: bold;
        }

        .contact {
            font-size: 14px;
        }

        .email:before {
            content: "✉️ ";
        }

        .phone:before {
            content: "📞 ";
        }

        .address:before {
            content: "🏠 ";
        }

        /* Education and Experience sections */
        .item {
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
            font-style: italic;
        }

        .date {
            font-size: 14px;
            font-style: italic;
            margin-bottom: 10px;
        }

        .description li {
            margin-bottom: 5px;
        }

        .show-certificate {
            background-color: #337ab7;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
        }

        .certificate {
            margin-top: 10px;
        }

        /* Certificate popup */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .popup-content {
            max-width: 80%;
            max-height: 80%;
            overflow: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
        }
    </style>