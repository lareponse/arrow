

here is the proposed structure. analyse, dont write code

/admin/
├── index.php                # Main dashboard with metrics
│
├── operator/                # Admin user management
│   ├── list.php             # All operators
│   ├── alter.php            # Create new or edit existing (id in args)
│   └── session.php          # Active sessions
│
├── article/                 # Content management
│   ├── list.php             # All articles + filters
│   └── alter.php            # Create new or edit existing (id in args)
│
├── event/                   # Event management
│   ├── list.php             # All events + filters
│   ├── alter.php            # Create new or edit existing (id in args)
│   └── booking/
│       ├── list.php         # Event bookings by event_id
│       ├── export.php       # CSV export of participants
│       └── notify.php       # Send notifications
│
├── training/                # Training management
│   ├── list.php             # All trainings + filters
│   ├── alter.php            # Create new or edit existing (id in args)
│   ├── program/
│   │   ├── alter.php        # Create/edit program (training_id + id in args)
│   │   └── list.php         # List program sessions
│   └── booking/
│       ├── list.php         # Training bookings by training_id
│       └── export.php       # CSV export of participants
│
├── trainee/                 # Trainees management
│   ├── list.php             # All trainees
│   └── alter.php            # Create new or edit existing (id in args)
│
├── trainer/                 # Trainer management
│   ├── list.php             # All trainers
│   └── alter.php            # Create new or edit existing (id in args)
│
├── assignment
│   ├── trainer.php    # trainer-training assignments
│   ├── trainee.php    # trainee-training assignments  
│   └── bulk.php       # batch operations
│
├── contact/                 # Contact requests
│   ├── list.php             # All requests + status filters
│   ├── view.php             # View single request (id in args)
│   └── reply.php            # Reply to request
│
├── subscription/            # Newsletter management
│   ├── list.php             # All subscriptions
│   ├── export.php           # CSV export
│   └── campaign.php         # Send newsletter
│
└── taxonomy/                # Categories/tags management
    ├── article.php          # article categories
    ├── event.php            # event categories
    └── training.php         # training categories

