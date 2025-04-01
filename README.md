# coproacademy

# Technical System Architecture Specification

## 1. Overview

The system follows a multi-tiered architecture with distinct separation between presentation, business logic, and data layers. Implementation follows a phased approach with incremental feature rollout and system expansion.

## 2. Core Architecture

### 2.1 Implementation Approach
- Phase 1 implements a PHP-based backend using a procedural architecture with strict separation of concerns
- Backend utilizes a Data Mapper pattern adapted to the procedural paradigm
- Frontend components are SSR, with interactive non obstrusive improvements in vanilla JS

### 2.2 Directory Structure
```
YAGNI/
├── app/
│   ├── controllers/
│   │   ├── back.php          # Single backend controller for all entities
│   │   └── front.php         # Frontend controller
│   ├── domains/              # Business logic
│   │   ├── blog.php          # Blog-specific business logic 
│   │   └── event.php         # Event-specific business logic
│   ├── mappers/              # Data access layer
│   │   ├── blog_mapper.php   # Blog data persistence
│   │   └── event_mapper.php  # Event data persistence
│   ├── services/             # Shared services
│   │   ├── oauth.php         # OAuth authentication service
│   │   ├── router.php        # Router implementation
│   │   └── yagni.php         # CMS core functionality
│   │   ├── oauth.php         # OAuth authentication service
│   │   └── yagni.php         # CMS core functionality
│   └── views/
│       ├── back/             # Backend templates
│       │   ├── blog/
│       │   └── events/
│       └── front/            # Frontend templates
│           ├── blog/
│           └── events/
│
├── config/
│   ├── app.php              # Application settings (paths, features, etc.)
│   ├── database.php         # Database connection parameters
│   ├── environment.php      # Environment-specific settings (dev/prod/test)
│   ├── mappers.php          # Data mapper configuration
│   ├── oauth.php            # OAuth configuration settings
│   ├── routes.php           # Route definitions
│   └── security.php         # Security settings (CSRF, sessions, etc.)
└── public/                   # Public web root
    ├── assets/               # Frontend UI assets
    │   ├── css/              # Stylesheets
    │   │   ├── base_1.css    # Core styles, smallest viewport, variables
    │   │   ├── base_2.css    # Content pivot point 1
    │   │   ├── base_3.css    # Content pivot point 2
    │   │   ├── base_4.css    # Content pivot point 3
    │   │   └── components/   # Component-specific styles
    │   │       ├── blog.css  # Blog component styles with responsive adjustments
    │   │       └── event.css # Event component styles with responsive adjustments
    │   ├── fonts/            # Web fonts
    │   ├── js/               # JavaScript files
    │   │   └── kortex.js     # Main application script
    │   └── uimage/           # UI images only
    ├── dash/                 # Backend web root
    │   ├── assets/           # Backend-specific assets
    │   │   ├── css/          # Backend stylesheets
    │   │   │   └── base.css  # Main backend styles
    │   │   ├── js/           # Backend scripts
    │   │   │   └── kortex.js # Main backend application script
    │   │   └── images/       # Backend UI images
    │   └── index.php         # Backend entry point
    ├── media/                # Content media at root level
    │   ├── documents/        # Downloadable documents
    │   ├── images/           # Content images
    │   └── videos/           # Video content
    ├── index.php             # Frontend entry point
    └── oauth-callback.php    # OAuth redirect endpoint

```
## 3. Component Breakdown

### 3.1 Frontend Layer
- **User Interface**: Responsive web application
- **Blog/News System**: Content management for publishing and displaying articles
- **Event Calendar**: Time-based event management and display functionality
- **Resource Library**: Digital asset management for documents and media

### 3.2 Core System
- **Komer Backend**: Central application server handling business logic and request processing
  - Implemented in PHP using procedural programming (no namespaces or OOP)
  - Organized by functional modules in the app/ directory
  - Uses a Data Mapper pattern for data persistence
- **Database**: Persistent data storage (relational)
- **Email System**: Transactional and marketing email processing

### 3.3 Routing Mechanism
- The `index.php` file acts as the main router
- Reads an `action` parameter via `$_GET` and delegates processing to the relevant module
- Module loading occurs via `include` statements

## 4. Data Handling

### 4.1 Data Mapper Pattern (Procedural Implementation)
- Centralizes database read/write operations
- Acts as intermediary between associative arrays and database
- Business modules pass associative arrays to the Mapper
- Mapper transforms arrays into SQL queries via PDO
- No business logic exists in database access functions
- Business layer remains unaware of SQL structure

### 4.2 Complex Relationships Implementation
- Handles conditional insertion of related entities (e.g., tags)
- Manages many-to-many relationships via junction tables (e.g., post_tag)
- Data structures for relationships follow the pattern:
```
[
  'post' => [...],
  'tags' => [[...], [...]],
  'post_tag' => [[...]]
]
```

### 4.3 Technical Capabilities
- Conditional insertion (ON DUPLICATE KEY)
- Dynamic ID retrieval via SELECT after insertion
- Batch operations support (multi-insert)
- Many-to-many relationship handling via junction tables

## 5. Phased Implementation

### 5.1 Phase 1
- Direct connections between components
- Frontend components communicate directly with Komer backend
- Backend maintains direct connections to database and email system
- Focus on core entities: articles, events, resources, and users

### 5.2 Phase 2
- Introduction of specialized subsystems:
  - **Payment System**: Financial transaction processing with payment gateway integration
  - **User Account System**: Authentication, authorization, and user profile management

### 5.3 Phase 3
- API-centric architecture:
  - **API Gateway**: Centralized entry point for managing, routing, and securing API requests
  - **RISE UP LMS**: Learning Management System integration via API
  - **Webinar Platform**: Video conferencing and webinar management functionality
- Reduced direct dependencies between systems

## 6. Technical Considerations

### 6.1 Architecture Benefits
- Strong separation of responsibilities
- System extensibility through modular architecture
- Compatible with complex business layers without disrupting data access
- Testable and maintainable codebase

### 6.2 Future Roadmap
- Specify CRUD functions per module
- Create one Mapper file per table
- Automate SQL query generation (generic approach)
- Implement transaction system for multi-step operations
- Transition to API-mediated architecture in later phases

### 6.3 Integration Patterns
- Phase 1-2: Direct point-to-point integration
- Phase 3: API Gateway as integration hub
- Bidirectional connections for real-time or event-driven communication
- External system isolation behind API layer
